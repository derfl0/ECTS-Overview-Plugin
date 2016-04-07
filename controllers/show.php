<?php

class ShowController extends StudipController {

    public function __construct($dispatcher) {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }

    public function before_filter(&$action, &$args) {

        if (Request::isXhr()) {
            $this->set_content_type('text/html;Charset=windows-1252');
            $this->set_layout(null);
        } else {
            $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        }
    }

    public function index_action($id = null) {
        // Load edit flag
        $this->edit = Request::get('edit');
        if ($this->edit) {
            PageLayout::addScript($this->plugin->getPluginURL().'/assets/application.js');
        }

        $overviews = EctsOverview::findByUser_id($GLOBALS['user']->id);

        // Create default if we got nothing
        if (!$overviews) {
            $overviews[] = EctsOverview::create(array(
                        'user_id' => $GLOBALS['user']->id,
                        'title' => _('Unbenannt')
            ));
        }

        // Transform into Collection
        $this->overviews = SimpleORMapCollection::createFromArray($overviews);

        // If we were given an id select the chosen overview
        if ($id) {
            $this->overview = $this->overviews->find($id);
        }

        // If for any reason we didnt select any select the first
        $this->overview = $this->overview ? : $this->overviews[0];

        // Push new data if required
        if (Request::submitted('save')) {
            $this->overview->title = Request::get('title');
            $this->overview->autofill = Request::get('autofill') ? : 0;
            $ects = Request::getArray('ects');
            $active = Request::getArray('active');

            foreach ($this->overview->courses as $course) {
                $course->ects = $ects[$course->seminar_id] ? : $course->course->ects;
                $course->active = $active[$course->seminar_id] ? : 0;
                $course->store();
            }
            $this->overview->store();
        }

        // Reload the data
        $this->overview->reload();

        // Sort for all semester
        foreach (Semester::getAll() as $semester) {
            $this->semester[$semester->name] = $this->overview->courses->findBy('semester_id', $semester->id);

            // Filter if not in edit mode
            if (!$this->edit) {
                $this->semester[$semester->name] = $this->semester[$semester->name]->findBy('active', 1);
            }

            foreach ($this->semester[$semester->name] as $course) {
                $this->sum += $course->ects;
            }
        }

        // Build navigation
        foreach ($this->overviews as $overview) {
            Navigation::addItem("/tools/ectsoverviewplugin/$overview->id", new AutoNavigation($overview->title, $this->url_for("show/index/$overview->id")));
        }

        // If we got fallback redo navigation thingy
        Navigation::activateItem("/tools/ectsoverviewplugin/{$this->overview->id}");

        $sidebar = Sidebar::Get();
        $sidebar->setImage('sidebar/log-sidebar.png');
        $actions = new ActionsWidget();
        $actions->addLink(_('Weitere Statistik erzeugen'), $this->url_for('show/createNew'));
        $actions->addLink(_('Statistik löschen'), $this->url_for("show/delete/{$this->overview->id}"));
        $sidebar->addWidget($actions);

    }

    public function createNew_action() {
        $overview = EctsOverview::create(array(
                    'user_id' => $GLOBALS['user']->id,
                    'title' => _('Unbenannt')
        ));
        $this->redirect("show/index/$overview->id");
    }

    public function delete_action($id) {
        $this->overview = EctsOverview::find($id);
        if (Request::submitted('delete')) {
            CSRFProtection::verifyRequest();
            $this->overview->delete();
            $this->redirect('show/index');
        }
        if (Request::submitted('abort')) {
            $this->redirect('show/index');
        }
    }

    // customized #url_for for plugins
    function url_for($to) {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }

}
