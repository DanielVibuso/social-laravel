<template>
    <div>
    <h2>login</h2>
    <form @submit.prevent="login">
            <input type="email" placeholder="Email" v-model="user.email">
            <input type="password" placeholder="Password" v-model="user.password">
            <button type="submit">Login</button>
    </form>
    <twitter-login></twitter-login>
   </div>
</template>
    
<script setup>
import http from '@/services/http.js'
import { reactive } from 'vue';
import { useAuthStore } from '@/stores/auth';
import TwitterLogin from '@/components/TwitterLogin.vue';

const auth = useAuthStore();

const user = reactive({
    email:'',
    password:''
})

async function login(){
    try {
        const {data} = await http.post('/api/auth/login', user)
        console.log(data.data.token)
        auth.setToken(data.data.token)
        auth.setUser(JSON.stringify(data.data.user))
        auth.setPermissions(data.data.permissions)
    } catch (error) {
        console.log(error?.response?.data?.errors)
    }
}
</script>
    
<style>
    
</style>