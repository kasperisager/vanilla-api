<!DOCTYPE html>
<html>
<head>
   {asset name="Head"}
</head>
<body id="{$BodyID}" class="{$BodyClass}">

   <div class="navbar navbar-static-top">
      <div class="navbar-inner">
         <div class="container">
            <a class="brand" href="{link path="api"}">
               {t c="Vanilla API Explorer"}
            </a>
            <ul class="nav">
               <li><a href="https://github.com/kasperisager/VanillaAPI/wiki">Developer Documentation</a></li>
            </ul>
            {*<embed src="http://ghbtns.com/github-btn.html?user=kasperisager&repo=VanillaAPI&type=watch&count=true&size=large" allowtransparency="true" frameborder="0" scrolling="0" width="170" height="30">*}
         </div>
      </div>
   </div>

   {asset name="Content"}
   {asset name="Foot"}
</body>
</html>