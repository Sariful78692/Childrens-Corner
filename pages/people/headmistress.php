<?php include '../layout/header.php'; ?>

<div class="hm-page">

  <!-- HERO -->
  <section class="hm-hero reveal" style="background-image: url(<?= (base_url) ?>assets/image/childrenscorner.jpg); background-repeat:no-repeat; background-position:center; background-size: cover;">
    <div class="hm-hero-content">
      <span class="hm-hero-badge reveal">Leadership Message</span>
      <h1 class="hm-hero-title reveal">Head Mistress Desk</h1>
      <div class="hm-hero-underline reveal"></div>
      <p class="hm-hero-text reveal">
        A message of inspiration, dedication and leadership from our respected Head Mistress.
      </p>
    </div>

  </section>

  <!-- CONTENT -->
  <div class="hm-container">
    <div class="hm-glass-card">
      <div class="hm-grid">

        <!-- LEFT PROFILE -->
        <div class="hm-profile">
          <div class="hm-profile-card">
            <img class="hm-photo" src="<?= base_url ?>assets/image/Head Mistress.jpg" alt="Rita Ghosh">

            <h2 class="hm-name">Rita Ghosh</h2>
            <p class="hm-role">Head Mistress</p>
            <p class="hm-degree">M.A.(Geography), B.Ed.</p>

            <div class="hm-info">
              <div class="hm-info-item">
                <div class="hm-dot"></div>
                <span>Serving the institution with dedication, leadership and responsibility.</span>
              </div>

              <div class="hm-info-item">
                <div class="hm-dot"></div>
                <span>Committed to building strong character and values among students.</span>
              </div>

              <div class="hm-info-item">
                <div class="hm-dot"></div>
                <span>Leading the school’s progress with vision and discipline.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT MESSAGE -->
        <div class="hm-message">

          <span class="hm-badge">HEAD MISTRESS</span>
          <h2 class="hm-title">A Message from Rita Ghosh</h2>
          <p class="hm-subtitle">
            This school is not just an institution — it is a part of my life, my dreams, and my purpose.
          </p>

          <div class="hm-divider"></div>

          <div class="hm-text">
            <p>
              I am Rita Ghosh. Currently, I am trying to fulfil the responsibility of the head teacher of this school
              with the help of all my parents and teachers. This important responsibility was given on a special day
              in the past by the founder of this school, Zahurul Haque Halder Mahashay, (of course to me he is “Zahur Da”)
              through a small ceremony.
            </p>

            <p>
              I am surprised to think how I have passed 37 long years through many setbacks. Every time I think it's not much longer,
              this illusion has to be cut, it doesn't become anymore. At present, the rector of this school is Sabir Hossain is made by me.
              That little boy has also tied me in Maya's bonds.
            </p>

            <p>
              However, according to the rules of nature, one day you have to go. But I pray to God that this little boy can hold the key
              to the development of the school in a strong hand and spread the reputation of the school around.
            </p>

            <div class="hm-quote">
              <p>
                To be honest, the school is my sleep, dream, wakefulness.
                What can be a greater achievement than being able to make many students as human beings in life.
              </p>
            </div>

            <p>
              The organization is now much better than before and so I dedicate the success of the hard work till now to 'Zahoor Da'
              and the rest of my colleagues who are alive or have left us.
            </p>
          </div>

          <div class="hm-signature">
            <div>
              <strong>— Rita Ghosh</strong><br>
              <span>Head Mistress</span>
            </div>
            <div>
              <span>Dedicated to excellence in education</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function revealOnScroll() {
    const reveals = document.querySelectorAll(".reveal");

    reveals.forEach((el) => {
      const windowHeight = window.innerHeight;
      const elementTop = el.getBoundingClientRect().top;
      const elementVisible = 120;

      if (elementTop < windowHeight - elementVisible) {
        el.classList.add("active");
      }
    });
  }

  window.addEventListener("scroll", revealOnScroll);
  window.addEventListener("load", revealOnScroll);
</script>


<?php include '../layout/footer.php'; ?>