<script setup>
import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import authV1BottomShape from "@images/svg/auth-v1-bottom-shape.svg?raw";
import authV1TopShape from "@images/svg/auth-v1-top-shape.svg?raw";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";

definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});

const form = ref({
  email: "",
  password: "",
  remember: false,
});

const errors = ref({
  email: undefined,
  password: undefined,
})
const ability = useAbility()
const route = useRoute()
const router = useRouter()

const isPasswordVisible = ref(false);
const refVForm = ref()

const login = async () => {
  try {
    const res = await $api("/login", {
      method: "POST",
      body: {
        email: form.value.email,
        password: form.value.password,
      },
      onResponseError({ response }) {
        errors.value = response._data.errors;
      },
    });

    const { accessToken, userData, userAbilityRules } = res;

    useCookie("userAbilityRules").value = userAbilityRules;
    ability.update(userAbilityRules);
    useCookie("userData").value = userData;
    useCookie("accessToken").value = accessToken;
    await nextTick(() => {
      router.replace(route.query.to ? String(route.query.to) : "/");
    });
  } catch (err) {
    console.error(err);
  }
};

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) login();
  });
};
</script>

<template>
  <div class="auth-wrapper d-flex align-center justify-center pa-4">
    <div class="position-relative my-sm-16">
      <!-- 👉 Top shape -->
      <VNodeRenderer
        :nodes="h('div', { innerHTML: authV1TopShape })"
        class="text-primary auth-v1-top-shape d-none d-sm-block"
      />

      <!-- 👉 Bottom shape -->
      <VNodeRenderer
        :nodes="h('div', { innerHTML: authV1BottomShape })"
        class="text-primary auth-v1-bottom-shape d-none d-sm-block"
      />

      <!-- 👉 Auth Card -->
      <VCard
        class="auth-card"
        max-width="460"
        :class="$vuetify.display.smAndUp ? 'pa-6' : 'pa-0'"
      >
        <VCardItem class="justify-center">
          <VCardTitle>
            <RouterLink to="/">
              <div class="app-logo">
                <VNodeRenderer :nodes="themeConfig.app.logo"  />
<!--                <h1 class="app-logo-title">Scan.vision2</h1>-->
              </div>
            </RouterLink>
          </VCardTitle>
        </VCardItem>

        <VCardText>
          <!--          <h4 class="text-h4 mb-1">-->
          <!--            Welcome to <span class="text-capitalize">{{ themeConfig.app.title }}</span>! 👋🏻-->
          <!--          </h4>-->
          <!--          <p class="mb-0">-->
          <!--            Please sign-in to your account and start the adventure-->
          <!--          </p>-->
        </VCardText>

        <VCardText>
          <VForm
            ref="refVForm"
            @submit.prevent="onSubmit"
            style="min-width: 350px; max-width: 400px; width: 100%"
          >
            <VRow>
              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.email"
                  autofocus
                  label="Email"
                  type="email"
                  placeholder="johndoe@email.com"
                  :error-messages="errors.email"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="form.password"
                  label="Пароль"
                  placeholder="············"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  :error-messages="errors.password"
                />

                <!-- remember me checkbox -->
                <div
                  class="d-flex align-center justify-space-between flex-wrap my-6"
                >
                  <VCheckbox v-model="form.remember" label="Запам'ятати мене" />
                </div>

                <!-- login button -->
                <VBtn block type="submit"> Вхід </VBtn>
              </VCol>

              <VCol cols="12" class="d-flex align-center">
                <VDivider />
                                <span class="mx-4 text-high-emphasis">або</span>
                <VDivider />
              </VCol>


              <!-- create account -->
              <VCol
                cols="12"
                class="text-center"
              >
                <VBtn block type="submit" color="secondary" :to="{ name: 'register' }">
                  Зареєструватись </VBtn>

                <!--                <RouterLink-->
                <!--                  class="text-primary ms-1"-->
                <!--                  :to="{ name: 'register' }"-->
                <!--                >-->
                <!--                  Create an account-->
                <!--                </RouterLink>-->

<!--                <span>New on our platform?</span>-->
<!--                <RouterLink-->
<!--                  class="text-primary ms-1"-->
<!--                  :to="{ name: 'register' }"-->
<!--                >-->
<!--                  Create an account-->
<!--                </RouterLink>-->
              </VCol>
<!--              <VCol-->
<!--                cols="12"-->
<!--                class="d-flex align-center"-->
<!--              >-->
<!--                <VDivider />-->
<!--                <span class="mx-4">or</span>-->
<!--                <VDivider />-->
<!--              </VCol>-->

<!--              &lt;!&ndash; auth providers &ndash;&gt;-->
<!--              <VCol-->
<!--                cols="12"-->
<!--                class="text-center"-->
<!--              >-->
<!--                <AuthProvider />-->
<!--              </VCol>-->
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </div>
  </div>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";
</style>
