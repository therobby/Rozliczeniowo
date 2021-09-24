<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">APP</div>

                    <div class="card-body">
                        <v-form>
                            <v-container>
                                <v-col>
                                    <v-text-field label="Nickname" required />
                                    <v-text-field
                                        type="password"
                                        label="Password"
                                        required
                                    />
                                    <v-btn elevation="2" @click="login"
                                        >Login</v-btn
                                    >
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
        if (!this.$store.state?.userToken) {
            this.$router.push({name: "Login"});
        }
    },
    methods: {
        login() {
            window.axios.get("/api/user/login", {
                username: this.username,
                password: this.password
            }).then(e => {
                console.warn(e);
            });
        }
    }
};
</script>
