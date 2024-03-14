<template>
  <div class="p-pargo-info">
    <p><a class="a-button" href="https://mypargo.pargo.co.za/mypargo" style="" @click="doEvent"
          target="_blank"><b>Login</b>
      to your myPargo account</a></p>
    <!--create two equal divs -->

    <div class="item">
      <h2>South Africa</h2>
      <p><b>Email Pargo:</b> <a href="mailto:info@pargo.co.za">info@pargo.co.za</a></p>
      <p><b>Call Pargo:</b> <a href="tel:+27 21 447 3636"> +27 21 447 3636  </a></p>
      <p><b>About Pargo:</b> <a href="https://pargo.co.za/faq/" target="_blank">Frequently Asked Questions</a></p>
    </div>
    <div class="item">
      <h2>Egypt</h2>
      <p><b>Email Pargo:</b> <a href="mailto:support.eg@pargo.com">support.eg@pargo.com</a></p>
      <p><b>Call Pargo:</b> <a href="tel:+20 100 326 5919"> +20 100 326 5919  </a> </p>
      <p><b>About Pargo:</b> <a href="https://eg.pargo.com/faq/" target="_blank">Frequently Asked Questions</a></p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PargoInfo',
  methods: {
    doEvent: async () => {
      await fetch(`${OBJ.api_url}pargo/v1/get-signup-click-event`, {
          headers: {
          'X-WP-Nonce': OBJ.nonce,
        }
      }).then(response => console.log(response)).catch(error => console.error(error));
    }
  }
}
</script>

<style scoped>
.item {
  width: 40%;
}
.p-pargo-info {
  display: flex;
  flex-direction: row;
  border: thin solid;
  border-radius: 0.4rem;
  background-color: #FFF;

}

.a-button {
  background: #ffeb3b;
  padding: 15px;
  display: block;
  text-align: center;
  color: #333;
  text-decoration: none;
  margin-top: 40px;
  border-radius: 5px;
  border: 1px solid;

}

p {
  margin: 0;
  padding: 0.5rem;
}
</style>
