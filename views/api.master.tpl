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
                {*{module name="MeModule"}*}
            </div>
        </div>
    </div>

    {asset name="Content"}
    {asset name="Foot"}
</body>
</html>