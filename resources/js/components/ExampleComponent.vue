<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Logowanie</div>

                    <div class="card-body">
                        <v-form v-model="valid">
                            <v-container>
                                <v-col>
                                    <v-text-field
                                        label="Nickname"
                                        required
                                    />
                                    <v-text-field
                                        type="password"
                                        label="Password"
                                        required
                                    />
                                    <v-btn
                                        elevation="2"
                                        @click="login"
                                        >Login</v-btn>
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
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            login(){
                console.log(window.axios)
                console.log('Login clicked.')
                window.axios.get('/api/roles').then(e=>{
                    console.warn(e);
                })
            }
        }
    }
</script>
