<template>
  <div :class="styles.arrayList.item">
    <div :class="toolbarClasses" @click="expandClicked">
      <div :class="styles.arrayList.itemLabel">{{ label }}</div>
      <button
        :class="styles.arrayList.itemMoveUp"
        :disabled="!moveUpEnabled"
        type="button"
        @click="moveUpClicked"
      >
        ↑
      </button>
      <button
        :class="styles.arrayList.itemMoveDown"
        :disabled="!moveDownEnabled"
        type="button"
        @click="moveDownClicked"
      >
        ↓
      </button>
      <button :class="styles.arrayList.itemDelete" type="button" @click="deleteClicked">🗙</button>
    </div>
    <div :class="contentClasses">
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, type PropType } from "vue";
import { classes, type Styles } from "../styles";

const listItem = defineComponent({
  name: "ArrayListElement",
  props: {
    initiallyExpanded: {
      required: false,
      type: Boolean,
      default: false,
    },
    label: {
      required: false,
      type: String,
      default: "",
    },
    moveUpEnabled: {
      required: false,
      type: Boolean,
      default: true,
    },
    moveDownEnabled: {
      required: false,
      type: Boolean,
      default: true,
    },
    moveUp: {
      required: false,
      type: Function,
      default: undefined,
    },
    moveDown: {
      required: false,
      type: Function,
      default: undefined,
    },
    delete: {
      required: false,
      type: Function,
      default: undefined,
    },
    styles: {
      required: true,
      type: Object as PropType<Styles>,
    },
  },
  data() {
    return {
      expanded: this.initiallyExpanded,
    };
  },
  computed: {
    contentClasses(): string {
      return classes`${this.styles.arrayList.itemContent} ${
        this.expanded && this.styles.arrayList.itemExpanded
      }`;
    },
    toolbarClasses(): string {
      return classes`${this.styles.arrayList.itemToolbar} ${
        this.expanded && this.styles.arrayList.itemExpanded
      }`;
    },
  },
  methods: {
    expandClicked(): void {
      this.expanded = !this.expanded;
    },
    moveUpClicked(event: Event): void {
      event.stopPropagation();
      this.moveUp?.();
    },
    moveDownClicked(event: Event): void {
      event.stopPropagation();
      this.moveDown?.();
    },
    deleteClicked(event: Event): void {
      event.stopPropagation();
      this.delete?.();
    },
  },
});

export default listItem;
</script>
