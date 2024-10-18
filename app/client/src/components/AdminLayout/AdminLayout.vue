<script setup lang="ts">
  import "./AdminLayout.scss";
</script>

<template>
  <div>
    <div>
      <router-view :key="$route.fullPath" />
    </div>
  </div>
</template>
