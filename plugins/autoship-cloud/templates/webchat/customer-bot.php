<div id="autoship-customer-bot"></div>
<script>
  // <![CDATA[
  BotChat.App({
    directLine: {secret: <?php echo json_encode( $webchat_directline_secret ); ?>, webSocket: true},
    user: {
        id: <?php echo json_encode( $autoship_customer_id ); ?>,
        name: <?php echo json_encode( $customer_name ); ?>,
        tokenAuth: <?php echo json_encode( $token_auth ); ?>
    },
    //bot: {id: 'autoship'},
    resize: 'detect'
  }, document.getElementById("autoship-customer-bot"));
  // ]]>
</script>
