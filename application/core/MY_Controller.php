<?php

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 * @author		JLP
 * @copyright           2010-2013, James L. Parry
 * ------------------------------------------------------------------------
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */

    function __construct() {
        parent::__construct();
        $this->data = array();
        $this->data['title'] = "Top Secret Government Site";    // our default title
        $this->errors = array();
        $this->data['pageTitle'] = 'welcome';   // our default page
    }

    /**
     * Render this page
     */
    function render() {
        $this->data['menubar'] = $this->makemenu();
        //$this->data['menubar'] = $this->parser->parse('_menubar', $this->config->item('menu_choices'),true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);

        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }
    
    function restrict($roleNeeded = null) {
        $userRole = $this->session->userdata('userRole');
        if ($roleNeeded != null) {
          if (is_array($roleNeeded)) {
            if (!in_array($userRole, $roleNeeded)) {
              redirect("/");
              return;
            }
          } else if ($userRole != $roleNeeded) {
            redirect("/");
            return;
          }
        }
    }

    function makemenu(){
        $menulist = $this->config->item('menu_choices');
        //get role & name from session
        $userRole = $this->session->userdata('userRole');
        // make array, with menu choice for alpha
        $menu = array('menudata' => array($menulist['menudata']['alpha']));
        switch($userRole){
        // if user, add menu choice for beta and logout
            case ROLE_USER:
                array_push($menu['menudata'], $menulist['menudata']['beta']);
                array_push($menu['menudata'], $menulist['menudata']['logout']);
                break;
        // if admin, add menu choices for beta, gamma and logout
            case ROLE_ADMIN:
                array_push($menu['menudata'], $menulist['menudata']['beta']);
                array_push($menu['menudata'], $menulist['menudata']['gamma']);
                array_push($menu['menudata'], $menulist['menudata']['logout']);
                break;
        // if not logged in, add menu choice to login
            default:
                array_push($menu['menudata'], $menulist['menudata']['login']);
                
        }
        
        // return the choices array
        return $this->parser->parse('_menubar', $menu, true);
    }
    
    
}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */