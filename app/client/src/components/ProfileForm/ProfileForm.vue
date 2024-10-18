<script setup lang="ts">
  import axios from "axios";
  import { ref, onMounted } from "vue";
  import { useRouter } from "vue-router";
  import { useToast } from "vue-toastification";
  import useAuthComp from "@/composables/useAuthComp";

  // Access authenticated user data and logout function
  const { user, logout } = useAuthComp();

  // Initialize toast for notifications
  const toast = useToast();
  const router = useRouter();

  // Form data
  const form = ref({
    username: "",
    email: "",
  });

  // Form error messages
  const formErrors = ref({
    username: "",
    email: "",
  });

  // Flags for handling errors and loading state
  const updateError = ref(false);
  const loading = ref(false);

  // Populate form with user data on mount
  onMounted(() => {
    if (user.value) {
      form.value.username = user.value.username;
      form.value.email = user.value.email;
    }
  });

  // Validate the form fields
  const validateForm = (): boolean => {
    let isValid = true;
    formErrors.value.username = "";
    formErrors.value.email = "";

    if (!form.value.username.trim()) {
      formErrors.value.username = "Username is required";
      isValid = false;
    }
    if (!form.value.email.trim()) {
      formErrors.value.email = "Email is required";
      isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
      formErrors.value.email = "Email is invalid";
      isValid = false;
    }

    return isValid;
  };

  // Submit the form to update the profile
  const submitForm = async () => {
    if (!validateForm()) return;

    loading.value = true;
    updateError.value = false;

    try {
      await axios.patch(`/user/${user.value.id}`, form.value);
      toast.success("Profile updated successfully.");
    } catch (error: any) {
      updateError.value = true;
      if (error.errors) {
        // Assign backend validation errors to form fields
        formErrors.value.username = error.errors.username
          ? error.errors.username[0]
          : "";
        formErrors.value.email = error.errors.email
          ? error.errors.email[0]
          : "";
      } else {
        console.error("Error submitting form:", error);
        toast.error("An unexpected error occurred.");
      }
    } finally {
      loading.value = false;
    }
  };

  // Handle logout action
  const handleLogout = async () => {
    try {
      await logout(); // Assuming logout is an async function from useAuthComp
      toast.success("Logged out successfully.");
      router.push("/login"); // Redirect to login page after logout
    } catch (error) {
      console.error("Logout Error:", error);
      toast.error("An error occurred during logout.");
    }
  };
</script>

<template>
  <div class="auth-profile auth-login">
    <!-- Header Section -->
    <div class="auth-base__head">
      <h3 class="auth-base__title">My Profile</h3>
      <!-- Logout Button -->
      <button class="btn btn-danger btn-elevate" @click="handleLogout">
        <i class="fas fa-sign-out-alt"></i> Logout
      </button>
    </div>

    <!-- Profile Form -->
    <form class="kt-form auth-base__form" @submit.prevent="submitForm">
      <!-- Username Field -->
      <div class="input-group">
        <label for="username" class="form-label">Username</label>
        <input
          v-model="form.username"
          id="username"
          class="form-control"
          type="text"
          placeholder="Username"
          name="username"
          autocomplete="off"
          required
        />
        <span v-if="formErrors.username" class="error invalid-feedback">
          {{ formErrors.username }}
        </span>
      </div>

      <!-- Email Field -->
      <div class="input-group">
        <label for="email" class="form-label">Email</label>
        <input
          v-model="form.email"
          id="email"
          class="form-control"
          type="email"
          placeholder="Email"
          name="email"
          autocomplete="off"
          required
        />
        <span v-if="formErrors.email" class="error invalid-feedback">
          {{ formErrors.email }}
        </span>
      </div>

      <!-- General Update Error -->
      <span v-if="updateError" class="error invalid-feedback general-error">
        An error occurred while updating the profile.
      </span>

      <!-- Submit Button -->
      <div class="auth-base__actions">
        <button
          class="btn btn-brand btn-elevate auth-base__btn-primary"
          type="submit"
          :disabled="loading"
        >
          <span v-if="loading"> <i class="spinner"></i> Saving... </span>
          <span v-else>Save</span>
        </button>
      </div>
    </form>
  </div>
</template>
