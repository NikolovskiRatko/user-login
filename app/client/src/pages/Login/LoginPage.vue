<script setup lang="ts">
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import { useToast } from "vue-toastification"; // Optional: For better user notifications
  import useAuthComp from "@/composables/useAuthComp";
  import "./LoginPage.scss";

  // Form data
  const form = ref({
    email: "",
    password: "",
  });

  // Form error messages
  const formErrors = ref({
    email: "",
    password: "",
  });

  // Flags for handling authentication errors and loading state
  const authError = ref(false);
  const loading = ref(false);
  const staySignedIn = ref(true);

  // Router instance
  const router = useRouter();

  // Access the login function from the auth composable
  const { login } = useAuthComp();

  // Optional: Initialize toast for notifications
  const toast = useToast();

  // Validate the form fields
  const validateForm = () => {
    let isValid = true;
    formErrors.value.email = "";
    formErrors.value.password = "";

    if (!form.value.email) {
      formErrors.value.email = "Email is required";
      isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(form.value.email)) {
      formErrors.value.email = "Email is invalid";
      isValid = false;
    }

    if (!form.value.password) {
      formErrors.value.password = "Password is required";
      isValid = false;
    }

    return isValid;
  };

  // Submit the login form
  const submitForm = async () => {
    if (!validateForm()) return;

    loading.value = true;
    authError.value = false;

    try {
      await login({
        data: form.value,
        redirect: false, // We'll handle redirection manually
        remember: false,
        staySignedIn: staySignedIn.value,
      });

      // Optionally, show a success toast
      toast.success("Logged in successfully!");

      // Redirect to the desired route after successful login
      router.push("/admin/dashboard");
    } catch (error: any) {
      authError.value = true;

      // Optionally, display error message using toast
      toast.error(error.message || "Authentication failed");

      console.error("Login Error:", error);
    } finally {
      loading.value = false;
    }
  };
</script>

<template>
  <div class="auth-login">
    <div class="auth-base__head">
      <h3 class="auth-base__title">Login 1.3</h3>
    </div>
    <form class="kt-form auth-base__form" @submit.prevent="submitForm">
      <!-- Email Field -->
      <div class="input-group">
        <input
          v-model="form.email"
          class="form-control"
          type="email"
          placeholder="admin@example.com"
          name="email"
          autocomplete="off"
          required
          autofocus
        />
      </div>
      <span v-if="formErrors.email" class="error invalid-feedback">
        {{ formErrors.email }}
      </span>

      <!-- Password Field -->
      <div class="input-group">
        <input
          v-model="form.password"
          class="form-control"
          type="password"
          placeholder="Password"
          name="password"
          required
        />
      </div>
      <span v-if="formErrors.password" class="error invalid-feedback">
        {{ formErrors.password }}
      </span>

      <!-- General Authentication Error -->
      <span v-if="authError" class="error invalid-feedback">
        Authentication failed
      </span>

      <!-- Remember Me Checkbox -->
      <div class="row auth-base__extra">
        <div class="col">
          <label class="kt-checkbox">
            <input v-model="staySignedIn" type="checkbox" name="remember" />
            Remember Me
            <span></span>
          </label>
        </div>
        <div class="col kt-align-right">
          <!-- Uncomment if password reset functionality is needed -->
          <!--
          <router-link
            id="kt_login_forgot"
            to="/password/reset"
            class="auth-base__link"
          >
            Password Reset
          </router-link>
          -->
        </div>
      </div>

      <!-- Submit Button -->
      <div class="auth-base__actions">
        <button
          class="btn btn-brand btn-elevate auth-base__btn-primary"
          type="submit"
          :disabled="loading"
        >
          <span v-if="loading"> <i class="spinner"></i> Signing In... </span>
          <span v-else>Sign In</span>
        </button>
      </div>
    </form>
  </div>
</template>
