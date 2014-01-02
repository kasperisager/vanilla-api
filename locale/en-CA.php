<?php if (!defined('APPLICATION')) exit();

// Settings page

$Definition['API.Settings.Title']                = "Application Interface";
$Definition['API.Settings.Documentation']        = "Read the API documentation";

$Definition['API.Settings.Endpoint.Label']       = "Endpoint";
$Definition['API.Settings.Endpoint.Description'] = "You can access your forum's Application Interface (API) through this endpoint URL";

$Definition['API.Settings.Secret.Label']         = "Application Secret";
$Definition['API.Settings.Secret.Description']   = "This is the Application Secret used for signature based authentication. <b>Keep it secret!</b>";

$Definition['API.Settings.Refresh.Label']        = "Re-generate";
$Definition['API.Settings.Refresh.Description']  = "Clicking \"Re-generate\" will generate a new UUID v4 key. Please refer to %s for more information";
$Definition['API.Settings.Refresh.Link']         = "this article";
$Definition['API.Settings.Refresh.Notification'] = "Refresh the page to see the new Application Secret.";

// Error messages

$Definition['API.Error.Mapper']             = "APIs must be subclassed from the API Mapper";
$Definition['API.Error.Interface']          = "APIs must implement the API Interface";
$Definition['API.Error.MethodNotAllowed']   = "Method Not Allowed";
$Definition['API.Error.AuthRequired']       = "Authentication required for this endpoint";
$Definition['API.Error.ContentType']        = "Unsupported content type: ";

$Definition['API.Error.User.Missing']       = "Authentication failed: Username or email must be specified";
$Definition['API.Error.User.Invalid']       = "Authentication failed: The specified user doesn't exist";

$Definition['API.Error.Timestamp.Missing']  = "Authentication failed: A timestamp must be specified";
$Definition['API.Error.Timestamp.Invalid']  = "Authentication failed: The request is no longer valid";

$Definition['API.Error.Token.Missing']      = "Authentication failed: A token must be specified";
$Definition['API.Error.Token.Invalid']      = "Authentication failed: Token and signature do not match";

$Definition['API.Error.Class.Invalid']      = "The requested API was not found";
$Definition['API.Error.Controller.Missing'] = "No controller has been defined in the API";