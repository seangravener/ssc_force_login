<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name'           => 'Post Login',
  'pi_version'        => '.01',
  'pi_author'         => 'Sean Gravener',
  'pi_author_url'     => 'http://sean.gravener.net',
  'pi_description'    => 'After forced login, redirect the user to their original destination.',
  'pi_usage'          => Ssc_force_login::usage()
);

class Ssc_force_login {

  var $return_data;
  
  /**
   * Constructor
   *
   */
  function Ssc_force_login()
  {
    $this->EE =& get_instance();
  }
  
  function get_uri()
  {
    return $this->EE->uri->uri_string();
  }
  
  function redirect() 
  {
    // get the number of segments to ignore when redirecting to a new path
    $offset = $this->_get_param("offset", 1) + 1;

    // the default path to redirect to if no segments after the offset are present
    $uri    = $this->_get_param("default", '');
        
    /* 
      Offset the root rewrite the uri.
      For example, if the full uri is:
        /login/path/to/redirect/to

      and the offset is set to 1, the uri would be rewritten to:
        /path/to/redirect/to
    */
    if ($this->EE->uri->segment(2) != '') 
    {
      $uri = $this->EE->uri->uri_to_assoc($offset);
      $uri = $this->EE->uri->assoc_to_uri($uri);
    }

    return $uri;
  }
  

  // --------------------------------------------------------------------
  
  function _get_param($key, $default_value = '')
  {
    $val = $this->EE->TMPL->fetch_param($key);
    
    if($val == '') {
      return $default_value;
    }
    return $val;
  }
  
  
  function usage()
  {
    ob_start(); 
    ?>
    
    After forced login, redirect the user to their original destination. For example,
    if a user were to bookmark their "account" page, and try to access that page before logging
    in, you would probably redirect them to a login page first. Once they've logged in, it's 
    only courteous to redirect them to where they initially intended to go.

    To use, place this plugin's tag in EE's login form return="" parameter.

    Example:
      
      A user wants to access their account, so they goto http://mysite.com/my-account. Before they
      can access their account, they need to login. Using EE's {redirect} tag, they are redirected 
      to the login page:

        {redirect="login/{exp:ssc_force_login:get_uri}"}

      The URI they were initially trying to access is then prefixed with a login template. The login
      template is a regular EE template.

      Here, the login form has a path of http://mysite.com/login/. In the login template, you would 
      use EE's member login tag:

      {exp:member:login_form return="{exp:ssc_force_login:redirect default='my-account' offset='1'}"}
        ... login form ...
      {/exp:member:login_form}

      Once the user is logged in, EE will redirect them to their original destination.

    Offset

    Use the offset is to ignore login templates.
      For example, if the full uri is:
        /login/path/to/redirect/to

      and the offset is set to 1, the uri would be rewritten to:
        /path/to/redirect/to

    <?php
    $buffer = ob_get_contents();
  
    ob_end_clean(); 

    return $buffer;
  }

  // --------------------------------------------------------------------

}
// END CLASS

/* End of file */