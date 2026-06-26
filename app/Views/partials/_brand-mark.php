<?php
/** Hawkins building-mark logo. Pass $markClass for an extra class (e.g. onorange). */
$markClass = $markClass ?? '';
?>
<svg class="brand-mark <?= e($markClass) ?>" viewBox="65 -4 162 184" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
  <polygon class="ink" points="126.93 40.76 184.81 13.85 201.4 25.12 201.4 134 177.62 141.19 176.99 25.74 133.82 46.7 135.07 59.84 126.93 60.78 126.93 40.76" />
  <polygon class="ink" points="208.91 13.85 221.11 1.02 221.11 144.63 142.89 174.04 70.62 146.82 81.88 137.13 144.98 159.97 207.35 135.57 208.91 13.85" />
  <polygon class="accent" points="101.28 71.11 158.85 43.89 175.43 55.15 175.43 144.01 152.91 152.77 150.4 56.4 107.85 75.49 107.85 87.38 101.28 90.19 101.28 71.11" />
  <polygon class="muted" points="70.62 129.61 80.32 133.06 80.32 106.46 124.12 85.19 124.12 147.77 142.58 156.21 148.53 154.02 148.53 83 131.32 72.99 70.62 99.58 70.62 129.61" />
</svg>
<?php $markClass = ''; /* reset for next include */ ?>
