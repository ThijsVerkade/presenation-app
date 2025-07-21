<template>
  <div class="display-container" :style="displayStyle">
    <div v-if="currentSlide" class="slide-content">
      <!-- Display the current slide content here -->
      <h1>{{ currentSlide.title }}</h1>
      <div v-html="currentSlide.content"></div>

      <!-- Display media assets if available -->
      <div v-if="currentAsset" class="media-container">
        <img v-if="currentAsset.type === 'image'" :src="currentAsset.url" alt="Slide media">
        <video v-else-if="currentAsset.type === 'video'" :src="currentAsset.url" controls autoplay></video>
      </div>
    </div>
    <div v-else class="no-slide">
      <h2>Waiting for active slide...</h2>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    display: Object
  },

  data() {
    return {
      currentSlide: null,
      currentAsset: null
    };
  },

  computed: {
    displayStyle() {
      if (this.display && this.display.resolution) {
        const { width, height } = this.display.resolution;
        return {
          width: `${width}px`,
          height: `${height}px`,
          aspectRatio: `${width} / ${height}`
        };
      }
      return {};
    }
  },

  mounted() {
    // Set up Echo channel to listen for real-time updates
    Echo.channel(`display.${this.display.slug}`)
      .listen('SlideActivated', (e) => {
        this.currentSlide = e.slide;

        // Find the asset for this display
        if (e.slide.assets && e.slide.assets.length) {
          this.currentAsset = e.slide.assets.find(asset =>
            asset.display_id === this.display.id
          );
        } else {
          this.currentAsset = null;
        }
      });
  },

  beforeUnmount() {
    // Clean up Echo channel subscription
    Echo.leave(`display.${this.display.slug}`);
  }
};
</script>

<style scoped>
.display-container {
  position: relative;
  margin: 0 auto;
  overflow: hidden;
  background-color: #000;
  color: #fff;
}

.slide-content {
  padding: 2rem;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.media-container {
  margin-top: 2rem;
  text-align: center;
}

.media-container img,
.media-container video {
  max-width: 100%;
  max-height: 70vh;
}

.no-slide {
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  color: #ccc;
}
</style>
