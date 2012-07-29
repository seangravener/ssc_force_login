## Dynamic Redirection after Forced Login in Expression Engine

After forced login, use this plugin to redirect the user to their original destination. For example, if a user were to bookmark their "account" page, and try to access that page before logging in, you would probably redirect them to a login page first. Once they've logged in, it's only courteous to redirect them to where they initially intended to go.

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

### Offset parameter

Use the offset is to ignore login templates.
For example, if the full uri is:

	/login/path/to/redirect/to

and the offset is set to 1, the uri would be rewritten to:

	/path/to/redirect/to