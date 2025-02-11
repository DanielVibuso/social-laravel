import { defineStore } from "pinia";
import { ref } from "vue";

export const useAuthStore = defineStore('auth', () => {
    const token = ref(localStorage.getItem('token'))
    const user = ref(localStorage.getItem('user'))
    const permissions = ref(localStorage.getItem('permissions'))

    function setToken(tokenValue) {
        localStorage.setItem('token', tokenValue)
        token.value = tokenValue
    }

    function setUser(userValue) {
        localStorage.setItem('user', userValue)
        user.value = userValue
    }

    function setPermissions(permissionsValue) {
        localStorage.setItem('permissions', permissionsValue)
        permissions.value = permissionsValue
    }

    return {
        permissions,
        token,
        user,
        setToken, 
        setUser,
        setPermissions
    }
})