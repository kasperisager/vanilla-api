<!DOCTYPE html>
<html>
<head>
   {asset name="Head"}
</head>
<body id="{$BodyID}" class="{$BodyClass}">

   {assign var=Repo value="https://github.com/kasperisager/VanillaAPI"}
   {assign var=GhbBtns value="http://ghbtns.com/github-btn.html?user=kasperisager"}

   <div class="navbar navbar-static-top">
      <div class="navbar-inner">
         <div class="container">
            <a class="brand" href="{link path="api"}">{t c="Vanilla API"}</a>
            <ul class="nav">
               <li>{link path="api" text="Explorer"}</li>
               <li class="dropdown">
                  {link format="<a href='#' data-toggle='dropdown' class='%class'>%text <b class='caret'></b></a>" class="dropdown-toggle" path="api/wiki" text="Documentation"}
                  <ul class="dropdown-menu">
                     <li>
                        {link path="api/wiki" text="Index"}
                     </li>
                     <li class="divider"></li>
                     <li class="nav-header">{t c="Documentation"}</li>
                     <li>{link path="api/wiki/installation" text="Installation"}</li>
                     <li>{link path="api/wiki/configuration" text="Configuration"}</li>
                     <li>{link path="api/wiki/authentication" text="Authentication"}</li>
                     <li>{link path="api/wiki/annotations" text="Annotations"}</li>
                     <li>{link path="api/wiki/extending" text="Extending"}</li>
                  </ul>
               </li>
            </ul>
            <ul class="nav pull-right">
               {if $User.SignedIn}                 
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {$User.Name} <b class="caret"></b>
                     </a>
                     <ul class="dropdown-menu">
                        <li class="nav-header">{t c="Welcome!"}</li>
                        {profile_link text="Profile"}
                        {inbox_link text="Inbox"}
                        {bookmarks_link text="Bookmarks"}
                        {dashboard_link text="Dashboard"}
                        <li class="divider"></li>
                        <li>{link path="signinout"}</li>
                     </ul>
                  </li>
               {/if}
               {if !$User.SignedIn}                
                  <li>{link path="/entry/register" text="Register"}</li>
                  <li>{link path="signin" target="current" text="Sign in"}</li>
               {/if}
            </ul>
            <ul class="nav pull-right github-btns">
               <embed src="{$GhbBtns}&repo=VanillaAPI&type=watch&count=true" width="85" height="20">
               <embed src="{$GhbBtns}&repo=VanillaAPI&type=fork&count=true" width="90" height="20">
            </ul>
         </div>
      </div>
   </div>

   <div class="container">
      {asset name="Content"}
   </div>

   {asset name="Foot"}
   {event name="AfterBody"}
</body>
</html>