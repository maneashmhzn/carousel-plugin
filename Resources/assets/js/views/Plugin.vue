<template>
  <div>
    <b-carousel
      id="carousel1"
      style="text-shadow: 1px 1px 2px #333;"
      controls
      indicators
      background="#ababab"
      :interval="4000"
      img-width="1024"
      img-height="480"
      v-model="slide"
      @sliding-start="onSlideStart"
      @sliding-end="onSlideEnd"
    >

    <template v-for="slide in carouselContent">
      <b-carousel-slide
        :caption="slide.caption"
        :text="slide.text"
        :img-src="imgPath(slide.image)"
      />
    </b-carousel-slide>
    </template>
    </b-carousel>

  </div>
</template>

<script>
  export default {
      props:['data'],
    data() {
      return {
        carouselData:{},
        carouselContent:[],
        slide: 0,
        sliding: null
      }
    },
    mounted(){
      this.getData();
    },
    watch:{
      carouselData(){
        var self = this
        self. carouselContent = JSON.parse(self.carouselData.content)
      }
    },
    methods: {
      getData(){
        var self = this
        var url = window.laravel.vueBaseUrl +'api/carousel_plugin/read'
        var data = {
          id : self.data
        }
        axios.post(url,data).then(r=>{
          self.carouselData = r.data.data
        })
      },
      imgPath(img){
        return window.laravel.vueBaseUrl + img
      },
      onSlideStart(slide) {
        this.sliding = true
      },
      onSlideEnd(slide) {
        this.sliding = false
      }
    },

  }
</script>
