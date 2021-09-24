<template>
    <div class="container">
        <v-alert
        v-model="errorAlert"
        type="error">
        {{loginError}}
        </v-alert>
        <v-alert
        v-model="successAlert"
        type="success">
        Register successful
        </v-alert>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Logowanie</div>

                    <div class="card-body">
                            <v-form >
                                <v-container>
                                    <v-col>
                                        <v-text-field
                                            label="Username"
                                            v-model="username"
                                            :error-messages="usernameErrors"
                                            required
                                        />
                                        <v-text-field
                                            type="password"
                                            :error-messages="passwordErrors"
                                            label="Password"
                                            v-model="password"
                                            required
                                        />
                                        <v-btn
                                            elevation="2"
                                            @click="login"
                                            color="primary"
                                            >Login</v-btn>
                                        <v-btn
                                            elevation="2"
                                            @click="register"
                                            color="secondary"
                                            >Register</v-btn>
                                    </v-col>
                                </v-container>
                            </v-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: ()=>({
            valid: false,
            passwordErrors: [],
            usernameErrors: [],
            username: null,
            password: null,
            loginError: null,
            errorAlert: false,
            successAlert: false
        }),
        mounted() {
            if(this.$store.state?.innerMessages?.registerSuccessful){
                //TODO:
            }
        },
        methods: {
            submit(){
                return true
            },
            login(e){
                if(this.username && this.password){
                    window.axios.post("/api/user/login", {
                        username: this.username,
                        password: this.password
                    }).then(e => {
                        console.warn(e)

                    }).catch(err=>{
                        console.log(err.code ?? {err})
                        console.log(err.response.data?.password?.length, (err.response.data?.password?.length || 0), (err.response.data?.password?.length || 0) > 0)
                        if(err.response.status === 401){
                            this.passwordErrors = []
                            this.usernameErrors = []
                            if((err.response.data?.password?.length || 0) > 0) this.passwordErrors.push(err.response.data.password[0])
                            if((err.response.data?.username?.length || 0) > 0) this.usernameErrors.push(err.response.data.username[0])
                        } else {
                            this.loginError = "ERROR: " + err.response.statusText + " ("+ err.response.status +")"
                            this.errorAlert = true;
                        }
                    })
                } else {
                    this.passwordErrors = []
                    this.usernameErrors = []
                    if(!this.username) this.usernameErrors.push('Username required')
                    if(!this.password) this.passwordErrors.push('Password required')
                }
                e.preventDefault()
            },
            register(e){
                this.$router.push({name: "Register"})
                e.preventDefault()
            }
        }
    }
</script>
