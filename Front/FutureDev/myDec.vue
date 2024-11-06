<template>
  <h1>hello</h1>
</template>





<!--<template>-->
<!--  <div class="content-container">-->
<!--    <div class="file-item" v-for="content in contents" :key="content.demainID" @click="openContent(content.content_url)">-->
<!--      <div class="file-icon">-->
<!--        <img :src="content.iconPath" alt="Icon" class="content-image">-->
<!--      </div>-->
<!--      <div class="file-name">{{ content.title }}</div>-->
<!--      <div class="file-description">{{ content.description }}</div>-->
<!--    </div>-->
<!--  </div>-->
<!--</template>-->

<!--<script>-->
<!--import axios from 'axios';-->

<!--export default {-->
<!--  data() {-->
<!--    return {-->
<!--      contents: []-->
<!--    };-->
<!--  },-->
<!--  created() {-->
<!--    axios.get('http://localhost/DEproject/API/userDemainInfo.php')-->
<!--        .then(response => {-->
<!--          if(response.data.result === "success"){-->
<!--            this.contents = response.data.data;-->
<!--          } else {-->
<!--            console.error("Error fetching content:", response.data.message);-->
<!--          }-->
<!--        })-->
<!--        .catch(error => {-->
<!--          console.error("API error:", error);-->
<!--        });-->
<!--  },-->
<!--  methods: {-->
<!--    openContent(url) {-->
<!--      window.location.href = url;-->
<!--    }-->
<!--  }-->
<!--};-->
<!--</script>-->


<!--<style scoped>-->
<!--.content-container {-->
<!--  display: grid;-->
<!--  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));-->
<!--  gap: 20px;-->
<!--  padding: 20px;-->
<!--}-->
<!--.file-item {-->
<!--  background-color: #f1f3f4;-->
<!--  border-radius: 8px;-->
<!--  padding: 20px;-->
<!--  text-align: center;-->
<!--  cursor: pointer;-->
<!--  transition: transform 0.2s ease;-->
<!--}-->
<!--.file-item:hover {-->
<!--  transform: scale(1.05);-->
<!--}-->
<!--.file-icon {-->
<!--  width: 100%;-->
<!--  height: auto;-->
<!--  display: flex;-->
<!--  justify-content: center;-->
<!--  align-items: center;-->
<!--  overflow: hidden;-->
<!--}-->
<!--.content-image {-->
<!--  width: 90%;-->
<!--  height: auto;-->
<!--  border-radius: 8px;-->
<!--}-->
<!--.file-name {-->
<!--  margin-top: 10px;-->
<!--  font-size: 18px;-->
<!--  color: #333;-->
<!--}-->
<!--.file-description {-->
<!--  font-size: 14px;-->
<!--  color: #666;-->
<!--}-->
<!--</style>-->