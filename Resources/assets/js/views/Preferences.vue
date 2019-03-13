<template>
  <div class="">
    <h1>
    Slider Preferences
      <button type="button" class="btn btn-success" name="button" @click="showAddSlideModal"> Add New Slide</button>
      <button type="button" class="btn btn-success" name="button"  v-if="data.length > 0 " @click="saveCarousel">Save Carousel</button>
  </h1>

{{data.length}} Slides in this carousel
<div class="container-fluid">
  <div class="row">
    <template  v-for="(slide,index) in data">
      <div class="col-md-12 my-2">
        <img :src="slide.base64"class="img-fluid w-50">
        <p>{{slide.caption}}</p>
        <button type="button" name="button" class="btn btn-warning" @click="editSlide(slide,index)"> Edit</button>
        <button type="button" name="button" class="btn btn-danger" @click="removeSlide(slide,index)"> Remove</button>
      </div>
    </template>
  </div>
</div>

        <b-modal ref="addSlideModal" hide-footer title="Add Slide" header-class="bg-success">
            <div class="row">
              <div class="col-md-12 modal-content">
                <div class="form-group">
                  <label for=""> Slide Caption</label>
                    <input type="text" class="form-control" v-model="addData.caption"/>
                </div>
                <div class="form-group">
                  <label for=""> Slide Text</label>
                    <textarea name="name" rows="4" class="form-control" v-model="addData.text"></textarea>
                </div>
                <div class="form-group">
                  <label for=""> Slide Image</label>
                  <!-- <input type="file" name="" value=""> -->
                  <div class="">
                    <croppa
                    v-model="addData.croppa"
                        :width="332"
                        :height="126"
                        placeholder="Yes, you can modify the text here"
                        placeholder-color="#000"
                        :placeholder-font-size="16"
                        canvas-color="transparent"
                        :show-remove-button="true"
                        remove-button-color="black"
                        :show-loading="true"
                        :loading-size="50"
                        :quality="5"
                                :prevent-white-space="true"
                        loading-color="#606060">
                  </croppa>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <button @click.prevent="addSlide" class="btn btn-success">Add Slide !</button>
                </div>
              </div>
            </div>
        </b-modal>


        <b-modal ref="editSlideModal" hide-footer title="Edit Slide" header-class="bg-warning" @shown="showed">
            <div class="row">
              <div class="col-md-12 modal-content">
                <div class="form-group">
                  <label for=""> Slide Caption</label>
                    <input type="text" class="form-control" v-model="editData.caption"/>
                </div>
                <div class="form-group">
                  <label for=""> Slide Text</label>
                    <textarea name="name" rows="4" class="form-control" v-model="editData.text"></textarea>
                </div>
                <div class="form-group">
                  <label for=""> Slide Image</label>
                  <div class="">
                    <croppa
                    v-model="editData.croppa"
                      :initial-image="editData.image"
                        :width="332"
                        :height="126"
                        placeholder="Yes, you can modify the text here"
                        placeholder-color="#000"
                        :placeholder-font-size="16"
                        canvas-color="transparent"
                        :show-remove-button="true"
                        remove-button-color="black"
                        :show-loading="true"
                        :loading-size="50"
                        :quality="5"
                                :prevent-white-space="true"
                        loading-color="#606060">
                  </croppa>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <button @click.prevent="updateSlide()" class="btn btn-success">Update Slide !</button>
                </div>
              </div>
            </div>
        </b-modal>


  </div>
</template>

<script>
import 'vue-croppa/dist/vue-croppa.css'
import Vue from 'vue'
 import Croppa from 'vue-croppa'
 Vue.use(Croppa)
export default {
  data(){
    return{
      module_id :'',
      imgData:'',
      addData:   {
        caption:"",
        text:"",
              },
      editData: {
        caption:"",
        text:"",
        croppa:{},
      },
      data :[],
    }
  },
  methods:{
    showAddSlideModal(){
      this.$refs.addSlideModal.show()
    },
    addSlide(){
      var self = this
      self.addData.croppa.refresh()
      var formData = {
        caption:self.addData.caption,
        text:self.addData.text,
        image :  self.addData.croppa.originalImage,
        base64 :  self.addData.croppa.generateDataUrl ('image/jpeg', 5),
        imageData : self.addData.croppa.getMetadata()
      }
      self.data.push(formData)

      self.resetData('add')

      self.$refs.addSlideModal.hide()

    },

    editSlide(slide,index){
      var self = this
      self. editData.croppa.refresh()
      self.editData.caption = slide.caption
      self.editData.image = slide.image
      self.editData.base64 = slide.base64
      self.editData.imageData = slide.imageData
      self.editData.index = index
      self.$refs.editSlideModal.show()
      self. editData.croppa.refresh()
    },
    updateSlide(){
      var self = this
      var index = self.editData.index
      var slide ={}
      slide.caption = self.editData.caption
      slide.text = self.editData.text
      slide.image = self.editData.croppa.originalImage
      slide.base64 = self.editData.croppa.generateDataUrl ('image/jpeg', 5)
      slide.imageData = self.editData.croppa.getMetadata()
      self.data[index]  = slide
      self. editData.croppa.refresh()
      self.resetData('edit')
      self.$refs.editSlideModal.hide()
    },
    resetData(x){
      var self = this
      if(x == 'edit'){
        self.editData.caption = ''
        self.editData.text = ''
        self.editData.image = ''
        self.editData.base64 = ''
        self.editData.imageData = ''
        self.editData.index = ''
        self. editData.croppa.refresh()
      }
      if(x == 'add'){
        self.addData.caption = ''
        self.addData.text = ''
        self.addData.image = ''
        self.addData.base64 = ''
        self.addData.imageData = ''
        self. addData.croppa.refresh()
      }
    },

    showed(){
      var self = this
      self. editData.croppa.refresh()
      self.editData.croppa.applyMetadata(self.editData.imageData)
      self. editData.croppa.refresh()
    },
    cancelEditSlide(){
      var self = this
      self.editData ={}
      self.$refs.editSlideModal.hide()
    },
    removeSlide(slide,index){
      var self = this
      self.data.splice(index,1)
    },
    saveCarousel(){
      var self = this
      var formData =  {data: self.data}
      var url = window.laravel.vueBaseUrl +'api/admin/carousel_plugin/add'
      axios.post(url,formData).then(r=>{
        if(r.data.data){
          self.$parent.addPluginContent(r.data.data.id)
        }
      })

    }
  },
  mounted(){
    this.module_id = this.$parent.$parent.working.module_id
  }
}
</script>

<style lang="css" scoped>
</style>
