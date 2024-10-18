<script setup lang="ts">
  import { ref } from "vue";
  import { useRouter } from "vue-router";
  import { useToast } from "vue-toastification";
  import useAuthComp from "@/composables/useAuthComp"; // Import the composable

  // Form data
  const form = ref({
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
    agreement: false, // Agreement checkbox
  });

  // Form error messages
  const formErrors = ref({
    username: "",
    email: "",
    password: "",
    password_confirmation: "",
    agreement: "",
  });

  // Flags for handling errors and loading state
  const authError = ref(false);
  const loading = ref(false);

  // Access the register function from the auth composable
  const { register } = useAuthComp();
  const router = useRouter();
  const toast = useToast();

  // Validate the form fields
  const validateForm = (): boolean => {
    let isValid = true;
    formErrors.value.username = "";
    formErrors.value.email = "";
    formErrors.value.password = "";
    formErrors.value.password_confirmation = "";
    formErrors.value.agreement = "";

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
    if (!form.value.password.trim()) {
      formErrors.value.password = "Password is required";
      isValid = false;
    }
    if (form.value.password !== form.value.password_confirmation) {
      formErrors.value.password_confirmation = "Passwords do not match";
      isValid = false;
    }
    if (!form.value.agreement) {
      formErrors.value.agreement = "You must agree to the Terms and Conditions";
      isValid = false;
    }

    return isValid;
  };

  // Submit the registration form
  const submitForm = async () => {
    if (!validateForm()) return;

    loading.value = true;
    authError.value = false;

    try {
      await register({
        body: {
          username: form.value.username,
          email: form.value.email,
          password: form.value.password,
          password_confirmation: form.value.password_confirmation,
        },
        autoLogin: true,
      });

      toast.success("Registration successful! Redirecting to login...");
      router.push("/login");
    } catch (error: any) {
      authError.value = true;
      if (error.response && error.response.data.errors) {
        const errors = error.response.data.errors;
        formErrors.value.username = errors.username ? errors.username[0] : "";
        formErrors.value.email = errors.email ? errors.email[0] : "";
        formErrors.value.password = errors.password ? errors.password[0] : "";
        formErrors.value.password_confirmation = errors.password_confirmation
          ? errors.password_confirmation[0]
          : "";
      } else {
        toast.error("An unexpected error occurred during registration.");
      }
    } finally {
      loading.value = false;
    }
  };
</script>

<template>
  <div class="auth-register">
    <div class="auth-base__head">
      <h3 class="auth-base__title">Register Form</h3>
    </div>
    <form class="kt-form auth-base__form" @submit.prevent="submitForm">
      <!-- Username Field -->
      <div class="input-group">
        <input
          v-model="form.username"
          class="form-control"
          type="text"
          placeholder="Username"
          name="username"
          autocomplete="off"
          required
        />
      </div>
      <span v-if="formErrors.username" class="error invalid-feedback">
        {{ formErrors.username }}
      </span>

      <!-- Email Field -->
      <div class="input-group">
        <input
          v-model="form.email"
          class="form-control"
          type="email"
          placeholder="email@example.com"
          name="email"
          autocomplete="off"
          required
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

      <!-- Confirm Password Field -->
      <div class="input-group">
        <input
          v-model="form.password_confirmation"
          class="form-control"
          type="password"
          placeholder="Confirm Password"
          name="password_confirmation"
          required
        />
      </div>
      <span
        v-if="formErrors.password_confirmation"
        class="error invalid-feedback"
      >
        {{ formErrors.password_confirmation }}
      </span>

      <!-- Agreement Checkbox -->
      <div class="auth-base__extra">
        <label class="kt-checkbox">
          <input v-model="form.agreement" type="checkbox" required />
          I agree to the Terms and Conditions
          <span></span>
        </label>
        <span v-if="formErrors.agreement" class="error invalid-feedback">
          {{ formErrors.agreement }}
        </span>
      </div>

      <!-- General Registration Error -->
      <span v-if="authError" class="error invalid-feedback">
        Registration failed
      </span>

      <!-- Submit Button -->
      <div class="auth-base__actions">
        <button
          class="btn btn-brand btn-elevate auth-base__btn-primary"
          type="submit"
          :disabled="loading"
        >
          <span v-if="loading"> <i class="spinner"></i> Registering... </span>
          <span v-else>Register</span>
        </button>
      </div>

      <!-- Already have an account? -->
      <div class="auth-base__extra">
        <div class="row">
          <div class="col text-center">
            <router-link to="/login" class="auth-base__link">
              Already have an account? Login here.
            </router-link>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>
