<script>
 // Referenced from:
 // https://www.digitalocean.com/community/tutorials/vuejs-vue-modal-component
  export default {
    name: 'PargoModal',
    methods: {
      close() {
        this.$emit('close');
      },
    },
  };
</script>

<template>
  <transition name="p-a-modal-fade">
    <div class="p-a-modal-backdrop">
      <div class="p-a-modal"
        role="dialog"
        aria-labelledby="modalTitle"
        aria-describedby="modalDescription"
      >
        <header
          class="p-a-modal-header"
          id="modalTitle"
        >
          <slot name="header">
            This is the default title!
          </slot>
          <button
            type="button"
            class="btn-close"
            @click="close"
            aria-label="Close modal"
          >
            x
          </button>
        </header>

        <section
          class="p-a-modal-body"
          id="modalDescription"
        >
          <slot name="body">
            This is the default body!
          </slot>
        </section>

        <footer class="p-a-modal-footer">
          <slot name="footer">
            This is the default footer!
          </slot>
        </footer>
      </div>
    </div>
  </transition>
</template>

<style>
  .p-a-modal-backdrop {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .p-a-modal {
    position: relative;
    background: #FFFFFF;
    box-shadow: 2px 2px 20px 1px;
    overflow-x: auto;
    display: flex;
    flex-direction: column;
  }

  .p-a-modal-header,
  .p-a-modal-footer {
    padding: 15px;
    display: flex;
  }

  .p-a-modal-header {
    position: relative;
    border-bottom: 1px solid #eeeeee;
    color: #000;
    justify-content: space-between;
  }

  .p-a-modal-footer {
    border-top: 1px solid #eeeeee;
    flex-direction: column;
  }

  .p-a-modal-body {
    position: relative;
    padding: 20px 10px;
  }

  .btn-close {
    position: absolute;
    top: 0;
    right: 0;
    border: none;
    font-size: 20px;
    padding: 10px;
    cursor: pointer;
    font-weight: bold;
    color: #000;
    background: transparent;
  }

  .btn-green {
    color: white;
    background: #fff200;
    border: 1px solid #fff200;
    border-radius: 2px;
  }

  .p-a-modal-fade-enter,
  .p-a-modal-fade-leave-to {
    opacity: 0;
  }

  .p-a-modal-fade-enter-active,
  .p-a-modal-fade-leave-active {
    transition: opacity .5s ease;
  }
</style>