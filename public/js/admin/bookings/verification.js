function toggleCard(card) {
  // Close other cards (accordion behavior) - Optional, user implied expand on click, usually standard to close others
  const allCards = document.querySelectorAll('.verification-card');
  allCards.forEach(c => {
    if (c !== card) c.classList.remove('active');
  });

  card.classList.toggle('active');
}
