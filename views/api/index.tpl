{literal}
<script type="text/javascript">
$(function () {
    window.swaggerUi = new SwaggerUi({
        discoveryUrl: $("#WebRoot").val() + 'api/resources',
        //apiKey:"special-key",
        dom_id:"swagger-ui-container",
        supportHeaderParams: false,
        supportedSubmitMethods: ['get', 'post', 'put'],
        onComplete: function(swaggerApi, swaggerUi){
            if(console) {
                console.log("Loaded SwaggerUI")
                console.log(swaggerApi);
                console.log(swaggerUi);
            }
            $('pre code').each(function(i, e) {hljs.highlightBlock(e)});
        },
        onFailure: function(data) {
            if(console) {
                console.log("Unable to Load SwaggerUI");
                console.log(data);
            }
        },
        docExpansion: "none"
    });

    window.swaggerUi.load();
});
</script>
{/literal}

<div id="message-bar" class="swagger-ui-wrap"></div>

<div id="swagger-ui-container" class="swagger-ui-wrap"></div>