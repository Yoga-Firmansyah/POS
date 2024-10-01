<template>
  <div v-if="modalState">
    <div class="barcode-container" @click="modalClose">
      <StreamBarcodeReader
        :landscape="landscape"
        :torch="torch"
        :zoom="Number(zoom)"
        :autofocus="autofocus"
        :focus-distance="Number(focusDistance)"
        :ms-between-decoding="500"
        @decode="onDecode"
        @loaded="onLoaded"
        @result="onResult"
      />
    </div>
  </div>
</template>

<script>
// This is here for testing purposes
// import StreamBarcodeReader from './StreamBarcodeReader.vue'
import { StreamBarcodeReader } from "@teckel/vue-barcode-reader";
import SvgIcon from "@jamescoyle/vue-icon";

const barcodeScannedAudio = new Audio("/assets/barcode-scanned.mp3");

const initialState = {
  loaded: false,
  modalState: false,
  torch: false,
  zoom: 1,
  autofocus: true,
  focusDistance: 0,
  landscape: false,
  hasTorch: false,
  hasZoom: false,
  hasAutofocus: false,
  hasFocusDistance: false,
  debounce: false,
  debounceTimeout: null,
};

export default {
  components: { StreamBarcodeReader, SvgIcon },
  emits: [
    "update:modelValue",
    "update:openModal",
    "update:cameraDetails",
    "update:rawResult",
  ],
  props: {
    modelValue: {
      type: [String, Number],
      default: null,
    },
    openModal: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      ...initialState,
    };
  },
  watch: {
    openModal() {
      this.modalState = this.openModal;
    },
    hasAutofocus() {
      this.autofocus = this.hasAutofocus;
    },
    focusDistance() {
      this.sliderMovement();
    },
    zoom() {
      this.sliderMovement();
    },
  },
  beforeUnmount() {
    this.modalClose();
  },
  mounted() {},
  methods: {
    onLoaded() {
      this.loaded = true;
      if (!this.hasAutofocus) {
        this.autofocus = false;
      }
      console.log("loaded");
    },
    onDecode(decodedText) {
      barcodeScannedAudio.play();
      console.log("Barcode scanned:", decodedText);
      this.$emit("update:modelValue", decodedText);
      this.modalClose();
    },
    onResult(result) {
      console.log("Raw Result:", result);
      this.$emit("update:rawResult", JSON.parse(JSON.stringify(result)));
    },
    sliderMovement() {
      if (!this.debounce) {
        this.debounce = true;
        window.navigator?.vibrate?.(10);
        clearTimeout(this.debounceTimeout);
        this.debounceTimeout = setTimeout(() => {
          this.debounce = false;
        }, 10);
      }
    },
    modalClose() {
      this.$emit("update:cameraDetails", this.cameraDetails);
      Object.assign(this.$data, initialState);
      this.$emit("update:openModal", false);
    },
  },
};
</script>

<style scoped>
.barcode-container {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
