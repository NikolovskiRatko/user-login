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
    first_name: "",
    last_name: "",
    email: "",
  });

  // Form error messages
  const formErrors = ref({
    first_name: "",
    last_name: "",
    email: "",
  });

  // Flags for handling errors and loading state
  const updateError = ref(false);
  const loading = ref(false);

  // Populate form with user data on mount
  onMounted(() => {
    if (user.value) {
      form.value.first_name = user.value.first_name;
      form.value.last_name = user.value.last_name;
      form.value.email = user.value.email;
    }
  });

  // Validate the form fields
  const validateForm = (): boolean => {
    let isValid = true;
    formErrors.value.first_name = "";
    formErrors.value.last_name = "";
    formErrors.value.email = "";

    if (!form.value.first_name.trim()) {
      formErrors.value.first_name = "First name is required";
      isValid = false;
    }
    if (!form.value.last_name.trim()) {
      formErrors.value.last_name = "Last name is required";
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
        formErrors.value.first_name = error.errors.first_name
          ? error.errors.first_name[0]
          : "";
        formErrors.value.last_name = error.errors.last_name
          ? error.errors.last_name[0]
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
      <!-- First Name Field -->
      <div class="input-group">
        <label for="first_name" class="form-label">First Name</label>
        <input
          v-model="form.first_name"
          id="first_name"
          class="form-control"
          type="text"
          placeholder="First Name"
          name="first_name"
          autocomplete="off"
          required
        />
        <span v-if="formErrors.first_name" class="error invalid-feedback">
          {{ formErrors.first_name }}
        </span>
      </div>

      <!-- Last Name Field -->
      <div class="input-group">
        <label for="last_name" class="form-label">Last Name</label>
        <input
          v-model="form.last_name"
          id="last_name"
          class="form-control"
          type="text"
          placeholder="Last Name"
          name="last_name"
          autocomplete="off"
          required
        />
        <span v-if="formErrors.last_name" class="error invalid-feedback">
          {{ formErrors.last_name }}
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
