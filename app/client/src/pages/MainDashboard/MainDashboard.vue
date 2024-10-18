<script setup lang="ts">
  import { ref, onMounted } from "vue";
  import { PageWrapper } from "@/components";
  import ProfileForm from "@/components/ProfileForm/ProfileForm.vue";
  import { useRootStore } from "@/store/root";

  const isLoading = ref(false);
  const { setActiveClasses } = useRootStore();

  onMounted(() => {
    isLoading.value = true;
    setActiveClasses({
      main: "item_dashboard",
      sub: "item_dashboard",
      title: "strings.dashboard",
    });
  });
</script>

<template>
  <PageWrapper>
    <ProfileForm />
  </PageWrapper>
</template>
