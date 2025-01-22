<script setup>
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import { VNodeRenderer } from "@layouts/components/VNodeRenderer";
import { themeConfig } from "@themeConfig";
import registerMultiStepIllustrationDark from "@images/illustrations/register-multi-step-illustration-dark.png";
import registerMultiStepIllustrationLight from "@images/illustrations/register-multi-step-illustration-light.png";
import registerMultiStepBgDark from "@images/pages/register-multi-step-bg-dark.png";
import registerMultiStepBgLight from "@images/pages/register-multi-step-bg-light.png";
const { t } = useI18n();

const registerMultiStepBg = useGenerateImageVariant(
  registerMultiStepBgLight,
  registerMultiStepBgDark
);

definePage({
  meta: {
    layout: "blank",
    public: true,
  },
});

const currentStep = ref(0);
const isActiveStepValid = ref(false);

const isPasswordVisible = ref(false);
const isConfirmPasswordVisible = ref(false);
const registerMultiStepIllustration = useGenerateImageVariant(
  registerMultiStepIllustrationLight,
  registerMultiStepIllustrationDark
);

const items = [
  {
    title: t("register.Personal Information"),
    // subtitle: "Account Details",
    icon: "tabler-file-analytics",
  },
  {
    title: t("register.Questions"),
    // subtitle: "Enter Information",
    icon: "tabler-user",
  },
  {
    title: t("register.Acccount information"),
    // subtitle: "Payment Details",
    icon: "tabler-credit-card",
  },
];

const emptyItem = {
  name: null,
  nick_name: null,
  phone: null,
  region_id: null,
  answers: {},
  email: null,
  password: null,
  password_confirm: null,
  promoCode: null,
};
const formData = ref(emptyItem);

const references = ref({});
const stepValidated = ref(null);
const errors = ref({});
const ability = useAbility();
const route = useRoute();
const router = useRouter();

const check = async (step) => {
  try {
    const res = await $api(`/${step == 0 ? "checkFirst" : "checkSecond"}`, {
      method: "POST",
      body: formData.value,
      onResponseError({ response }) {
        errors.value = response._data.errors;
        stepValidated.value = currentStep.value;
      },
    });

    stepValidated.value = currentStep.value;
    if (res) {
      currentStep.value += 1;
      // afterSave(res.user.id);
    }
  } catch (err) {
    console.error(err);
  }
};

const store = async () => {
  try {
    const res = await $api(`/register`, {
      method: "POST",
      body: formData.value,
      onResponseError({ response }) {
        errors.value = response._data.errors;
        stepValidated.value = currentStep.value;
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

    // if (res) {
    //   currentStep.value += 1;
    //   // afterSave(res.user.id);
    // }
  } catch (err) {
    console.error(err);
  }
};

const onStepClick = () => {
  if (currentStep.value < 2) {
    check(currentStep.value);
  } else {
    store();
  }
};
const loadReferences = async () => {
  const res = await $api("/reference-by-key-guest", {
    method: "POST",
    body: { keys: ["region", "questions"] },
  });

  references.value = res;
  references.value.questions.map((v) => {
    formData.value.answers[v.id] = null;
  });
};

onMounted(() => {
  loadReferences();
});
</script>

<template>
  <RouterLink to="/">
    <div class="auth-logo d-flex align-center gap-x-3">
      <!-- <VNodeRenderer :nodes="themeConfig.app.logo" /> -->
      <h1 class="auth-title">
        {{ themeConfig.app.title }}
      </h1>
    </div>
  </RouterLink>

  <VRow no-gutters class="auth-wrapper">
    <VCol md="4" class="d-none d-md-flex">
      <!-- here your illustration -->
      <div class="d-flex justify-center align-center w-100 position-relative">
        <VImg
          :src="registerMultiStepIllustration"
          class="illustration-image flip-in-rtl"
        />

        <img
          class="bg-image position-absolute w-100 flip-in-rtl"
          :src="registerMultiStepBg"
          alt="register-multi-step-bg"
          height="340"
        />
      </div>
    </VCol>

    <VCol
      cols="12"
      md="8"
      class="auth-card-v2 d-flex align-center justify-center pa-10"
      style="background-color: rgb(var(--v-theme-surface))"
    >
      <VCard flat class="mt-12 mt-sm-10">
        <AppStepper
          v-model:current-step="currentStep"
          :items="items"
          :isActiveStepFreeze="true"
          :direction="$vuetify.display.smAndUp ? 'horizontal' : 'vertical'"
          icon-size="22"
          class="stepper-icon-step-bg mb-12"
        />

        <VWindow
          v-model="currentStep"
          class="disable-tab-transition"
          style="max-inline-size: 681px"
        >
          <VForm>
            <VWindowItem>
              <h4 class="text-h4">{{ $t("register.Personal Information") }}</h4>

              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.name"
                    :label="$t('register.name')"
                    :error-messages="errors.name"
                  />
                </VCol>
                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.nick_name"
                    :label="$t('register.nick_name')"
                    :error-messages="errors.nick_name"
                  />
                </VCol>

                <VCol cols="12" md="6">
                  <AppMaskTextField
                    v-model="formData.phone"
                    :label="$t('register.phone')"
                    :error-messages="errors.phone"
                  />
                </VCol>
                <VCol cols="12" md="6">
                  <AppSelect
                    :items="references.region"
                    v-model="formData.region_id"
                    :label="$t('register.region')"
                    :error-messages="errors.region_id"
                  />
                </VCol>
              </VRow>
            </VWindowItem>

            <VWindowItem>
              <h4 class="text-h4">{{ $t("Questions") }}</h4>
              <p class="text-danger">
                {{ errors?.answers ? errors?.answers[0] : "" }}
              </p>
              <VRow>
                <VCol cols="10" md="10">
                  <VExpansionPanels variant="accordion">
                    <VExpansionPanel
                      v-for="question in references.questions"
                      :key="question.id"
                    >
                      <VExpansionPanelTitle
                        :class="{
                          'bg-warning':
                            stepValidated == 1 &&
                            errors['answers.' + question.id],
                          'bg-success':
                            stepValidated == 1 &&
                            !errors['answers.' + question.id],
                        }"
                      >
                        {{ question.content }}
                      </VExpansionPanelTitle>
                      <VExpansionPanelText>
                        <VRadioGroup
                          v-model="formData.answers[question.id]"
                          :error-messages="errors['answers.' + question.id]"
                        >
                          <VRadio
                            v-for="answer in question.answers"
                            :key="answer.id"
                            :label="answer.content"
                            :value="answer.id"
                          />
                        </VRadioGroup>
                      </VExpansionPanelText>
                    </VExpansionPanel>
                  </VExpansionPanels>
                </VCol>
              </VRow>
            </VWindowItem>

            <VWindowItem>
              <h4 class="text-h4">{{ $t("Acccount information") }}</h4>
              <VRow>
                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.email"
                    :label="$t('register.email')"
                    :type="'email'"
                    :error-messages="errors.email"
                  />
                </VCol>
                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.promoCode"
                    :label="$t('register.promoCode')"
                    :error-messages="errors.promoCode"
                  />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.password"
                    :label="$t('register.password')"
                    placeholder="············"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                      isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                    "
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                    :error-messages="errors.password"
                  />
                </VCol>

                <VCol cols="12" md="6">
                  <AppTextField
                    v-model="formData.password_confirmation"
                    :label="$t('register.Confirm Password')"
                    placeholder="············"
                    :type="isConfirmPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="
                      isConfirmPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                    "
                    @click:append-inner="
                      isConfirmPasswordVisible = !isConfirmPasswordVisible
                    "
                    :error-messages="errors.password_confirmation"
                  />
                </VCol>
              </VRow>
            </VWindowItem>
          </VForm>
        </VWindow>

        <div class="d-flex flex-wrap justify-space-between gap-x-4 mt-6">
          <VBtn
            color="secondary"
            :disabled="currentStep === 0"
            variant="tonal"
            @click="currentStep--"
          >
            <VIcon icon="tabler-arrow-left" start class="flip-in-rtl" />
            {{ $t("Previous") }}
          </VBtn>

          <VBtn
            v-if="items.length - 1 === currentStep"
            color="success"
            @click="store"
          >
            {{ $t("submit") }}
          </VBtn>

          <VBtn v-else @click="onStepClick()">
            {{ $t("Next") }}

            <VIcon icon="tabler-arrow-right" end class="flip-in-rtl" />
          </VBtn>
        </div>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";

.illustration-image {
  block-size: 550px;
  inline-size: 248px;
}

.bg-image {
  inset-block-end: 0;
}
</style>
