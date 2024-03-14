<template>
    <label :class="[
        {'is-disabled' : disabled}
    ]">
        <slot></slot>
    </label>
</template>

<script>
export default {
    name: "InputLabel",
    props: {
        disabled: Boolean,
        default: false
    }
}
</script>

<style lang="scss" scoped>
    label {
        display: block;
        padding: 2px 0;

        &.is-disabled {
            opacity: 0.5;
            pointer-events: none;
            cursor: not-allowed;
        }
    }
</style>