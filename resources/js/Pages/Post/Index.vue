<script setup>
import {defineProps, onMounted, ref} from 'vue';

const props = defineProps({
    posts: Array,
});
const body = ref({
    title: '',
    body: '',
    user_id: ''
});
onMounted(() => {
    window.Echo.channel('store_post')
        .listen('.store_post', res => {
            props.posts.unshift(res.post);
            console.log('Событие store_post получено:', res);
        });

});
const sendMessage = () => {
    axios.post('/posts',
        body.value)
        .then(res => {
            props.posts.unshift(res.data);
            console.log('Ответ сервера:', res.data);
        })
        .catch(error => {
            console.error('Ошибка при отправке сообщения:', error);
        });
};
</script>
<template>
    <div class="w-1/2 mx-auto py-6">
        <div>
            <form @submit.prevent="sendMessage">
                <div>
                    <input v-model="body.title" name="title" class="rounded-full" type="text" placeholder="Post title">
                </div>
                <div>
                    <input v-model="body.body" name="body" class="rounded-full" type="text" placeholder="Post body">
                </div>
                <div>
                    <input v-model="body.user_id" name="user_id" class="rounded-full" type="text" placeholder="User ID">
                </div>
                <div>
                    <button type="submit" class="rounded-lg block w-48" style="border: 2px solid red; margin-top: 10px">
                        Send
                    </button>
                </div>
            </form>
        </div>
        <div v-if="posts.length > 0" class="mb-4">
            <h3>Posts</h3>
            <div v-for="post in posts" style="border: 2px solid green">
                <p><span style="font-style: italic">Title:</span> {{ post.title }}</p>
                <p><span style="font-style: italic">Body:</span> {{ post.body }}</p>
                <p><span style="font-style: italic">User:</span> {{ post.user }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
