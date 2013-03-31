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
            <a class="brand" href="{link path="$Repo"}">{t c="Vanilla API"}</a>
            <ul class="nav">
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">{t c="Documentation"} <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                     <li>
                        <a href="{$Repo}/wiki">{t c="Wiki"}</a>
                     </li>
                     <li class="divider"></li>
                     <li class="nav-header">{t c="Documentation"}</li>
                     <li><a href="{$Repo}/wiki/Installation">{t c="Installation"}</a></li>
                     <li><a href="{$Repo}/wiki/Annotations">{t c="Annotations"}</a></li>
                     <li><a href="{$Repo}/wiki/Extending">{t c="Extending"}</a></li>
                  </ul>
               </li>
            </ul>
            <ul class="nav pull-right">
               <embed src="{$GhbBtns}&repo=VanillaAPI&type=watch&count=true" width="85" height="20">
               <embed src="{$GhbBtns}&repo=VanillaAPI&type=fork&count=true" width="90" height="20">
               <embed src="{$GhbBtns}&type=follow&count=true" width="180" height="20">
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