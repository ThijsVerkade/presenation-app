<template>
  <div class="slide-editor">
    <h1>Edit Slide</h1>

    <div class="slide-form">
      <form @submit.prevent="saveSlide">
        <div class="form-group">
          <label for="title">Title</label>
          <input type="text" id="title" v-model="form.title" class="form-control">
        </div>

        <div class="form-group">
          <label for="content">Content</label>
          <textarea id="content" v-model="form.content" class="form-control" rows="5"></textarea>
        </div>

        <div class="form-group">
          <label>Status</label>
          <div class="status-options">
            <label>
              <input type="radio" v-model="form.status" value="draft"> Draft
            </label>
            <label>
              <input type="radio" v-model="form.status" value="active"> Active
            </label>
          </div>
        </div>

        <h2>Display Assets</h2>
        <div v-for="asset in form.assets" :key="asset.id" class="display-asset">
          <h3>{{ asset.display.name }}</h3>
          <div class="form-group">
            <label>Media Type</label>
            <select v-model="asset.type" class="form-control">
              <option value="image">Image</option>
              <option value="video">Video</option>
            </select>
          </div>

          <div class="form-group">
            <label>Media URL</label>
            <input type="text" v-model="asset.url" class="form-control">
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save Slide</button>
          <button type="button" @click="activateSlide" class="btn btn-success" v-if="form.status === 'active'">
            Activate Now
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    presentation: Object,
    slide: Object
  },

  data() {
    return {
      form: {
        title: this.slide.title,
        content: this.slide.content,
        status: this.slide.status || 'draft',
        assets: this.slide.assets || []
      }
    };
  },

  methods: {
    saveSlide() {
      this.$inertia.put(route('admin.slides.update', {
        presentation: this.presentation.id,
        slide: this.slide.id
      }), this.form);
    },

    activateSlide() {
      this.$inertia.post(route('admin.slides.activate', {
        slide: this.slide.id
      }));
    }
  }
};
</script>

<style scoped>
.slide-editor {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: bold;
}

.form-control {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.status-options {
  display: flex;
  gap: 1rem;
}

.status-options label {
  display: flex;
  align-items: center;
  font-weight: normal;
}

.status-options input {
  margin-right: 0.5rem;
}

.display-asset {
  margin-bottom: 2rem;
  padding: 1rem;
  border: 1px solid #eee;
  border-radius: 4px;
}

.form-actions {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
}

.btn {
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary {
  background-color: #4a5568;
  color: white;
}

.btn-success {
  background-color: #48bb78;
  color: white;
}
</style>
