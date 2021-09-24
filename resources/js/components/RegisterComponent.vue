<template>
    <div class="container">
        <v-alert
        v-model="alert"
        type="error">
        {{registerError}}
        </v-alert>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Register</div>

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
                                        <v-text-field
                                            type="password"
                                            :error-messages="passwordErrors"
                                            label="Confirm Password"
                                            v-model="passwordConf"
                                            required
                                        />
                                        <v-text-field
                                            type="email"
                                            :error-messages="emailErrors"
                                            label="Email"
                                            v-model="email"
                                            required
                                        />
                                        <v-btn
                                            elevation="2"
                                            @click="register"
                                            color="primary"
                                            >Register</v-btn>
                                        <v-btn
                                            elevation="2"
                                            @click="login"
                                            color="secondary"
                                            >Go back to login</v-btn>
                                    </v-col>
                                </v-container>
                            </v-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data: ()=>({
            passwordErrors: [],
            usernameErrors: [],
            emailErrors: [],
            username: null,
            password: null,
            passwordConf: null,
            email: null,
            registerError: null,
            alert: false
        }),
        mounted() {
        },
        methods: {
            login(e){
                this.$router.push({name: "Login"})
                e.preventDefault()
            },
            register(){
                if(this.username && this.password && this.email && this.passwordConf){
                    if(this.password === this.passwordConf){
                        window.axios.post("/api/user/register", {
                            username: this.username,
                            password: this.password,
                            email: this.email
                        }).then(e => {
                            console.warn(e)
                            if(e.status === 200){
                                this.$emit('register-succesfull', true);
                                this.$router.push({name: "Login"})
                            }
                        }).catch(err=>{
                            console.log(err.code ?? {err})
                            if(err.response.status === 401){
                                this.passwordErrors = []
                                this.usernameErrors = []
                                this.emailErrors = []
                                if((err.response.data?.password?.length || 0) > 0) this.passwordErrors.push(err.response.data.password[0])
                                if((err.response.data?.username?.length || 0) > 0) this.usernameErrors.push(err.response.data.username[0])
                                if((err.response.data?.email?.length || 0) > 0) this.emailErrors.push(err.response.data.email[0])
                            } else {
                                this.registerError = "ERROR: " + err.response.statusText + " ("+ err.response.status +")"
                                this.alert = true;
                            }
                        })
                    } else {
                        this.passwordErrors = []
                        this.passwordErrors.push('Passwords must be the same')
                    }
                } else {
                    this.passwordErrors = []
                    this.emailErrors = []
                    this.usernameErrors = []
                    if(!this.username) this.usernameErrors.push('Username required')
                    if(!this.email) this.emailErrors.push('Email required')
                    if(!this.password) this.passwordErrors.push('Password required')
                }
            }
        }
    }
</script>
