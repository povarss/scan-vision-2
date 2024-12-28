<script setup>
import navItems from '@/navigation/vertical'
import { themeConfig } from '@themeConfig'

// Components
import Footer from '@/layouts/components/Footer.vue'
import NavBarNotifications from '@/layouts/components/NavBarNotifications.vue'
import NavSearchBar from '@/layouts/components/NavSearchBar.vue'
import NavbarShortcuts from '@/layouts/components/NavbarShortcuts.vue'
import NavbarThemeSwitcher from '@/layouts/components/NavbarThemeSwitcher.vue'
import UserProfile from '@/layouts/components/UserProfile.vue'
import NavBarI18n from '@core/components/I18n.vue'
import { HorizontalNav2 } from '@layouts/components'
import { hasAccessRole } from "@layouts/plugins/casl";

// @layouts plugin
import { VerticalNavLayout } from '@layouts'
const hidePanels = true;
</script>

<template>
  <VerticalNavLayout :nav-items="navItems">
    <!-- 👉 navbar -->
    <template #navbar="{ toggleVerticalOverlayNavActive }">
      <div class="d-flex h-100 align-center">
        <IconBtn
          id="vertical-nav-toggle-btn"
          class="ms-n3 d-lg-none"
          @click="toggleVerticalOverlayNavActive(true)"
        >
          <VIcon
            size="26"
            icon="tabler-menu-2"
          />
        </IconBtn>
        <HorizontalNav2 :nav-items="navItems" />

        <NavSearchBar class="ms-lg-n3" v-if="!hidePanels" />
        <VSpacer />

        <NavBarI18n
          v-if="themeConfig.app.i18n.enable && themeConfig.app.i18n.langConfig?.length && !hidePanels"
          :languages="themeConfig.app.i18n.langConfig"
        />
        <NavbarThemeSwitcher v-if="!hidePanels" />
        <NavbarShortcuts v-if="!hidePanels"/>
        <NavBarNotifications class="me-1" v-if="!hidePanels" />
        <UserProfile />
      </div>
    </template>

    <!-- 👉 Pages -->
    <slot />

    <!-- 👉 Footer -->
    <template #footer>
      <Footer />
    </template>

    <!-- 👉 Customizer -->
    <TheCustomizer v-if="hasAccessRole(['admin']) && false"/>
  </VerticalNavLayout>
</template>
