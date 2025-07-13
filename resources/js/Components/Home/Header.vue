<template>
  <header class="text-white border-bottom py-3">
    <div class="container d-flex justify-content-between align-items-center">
      <!-- Логотип -->
      <div class="logo d-flex align-items-center">
        <img src="../../../../public/images/logo.png" alt="Логотип" height="40" class="me-2" />
        <span class="korinna">Turkistan Invest</span>
      </div>

      <!-- Навигация -->
      <nav class="d-flex align-items-center">
        <ul class="nav me-3">
          <li class="nav-item">
            <a class="nav-link text-white fw-medium" href="#">Басты бет</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-medium" href="#">Біз жайлы</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white fw-medium" href="#">Сұраныс беті</a>
          </li>
        </ul>
        <!-- Социальные иконки -->
        <div class="social-icons d-flex">
          <a href="https://instagram.com" target="_blank" class="text-white me-3 fs-5">
            <i class="fab fa-instagram"></i>
          </a>
          <a href="https://wa.me/77001234567" target="_blank" class="text-white me-3 fs-5">
            <i class="fab fa-whatsapp"></i>
          </a>
          <a href="https://t.me/yourchannel" target="_blank" class="text-white fs-5">
            <i class="fab fa-telegram"></i>
          </a>
        </div>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  name: "Header",
};
</script>

<style scoped>
.nav-link:hover,
.social-icons a:hover {
  color: #0d6efd;
  text-decoration: none;
}
</style>
