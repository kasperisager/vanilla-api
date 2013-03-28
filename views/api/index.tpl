{literal}
<script type="text/javascript">
$(function () {
   window.swaggerUi = new SwaggerUi({
      discoveryUrl: $('#WebRoot').val() + 'api/resources',
      dom_id: 'swagger-ui-container',
      supportHeaderParams: false,
      // Swagger UI doesn't support formParams
      supportedSubmitMethods: ['get'],
   });
   window.swaggerUi.load();
});
</script>
{/literal}

<div id="swagger-ui-container" class="swagger-ui-wrap container">

   <div id="message-bar" class="swagger-ui-wrap"></div>

</div>