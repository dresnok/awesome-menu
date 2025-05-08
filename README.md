## 🔗 Live Demo

👉 [Zobacz demo](http://company12.atwebpages.com/?next=awesome-menu)  

## 📝 Uwagi dotyczące stylów demonstracyjnych

W przykładowym pliku HTML, kontener `#app` został opatrzony tymczasowym stylem:
<body>
<div id="app" style="margin-top:5em; width:500px;">

# Vue 3 – Dynamiczne Menu z Teleportowanym Dropdownem

Ten projekt to responsywne, poziome menu zbudowane w Vue 3, które obsługuje sytuacje, gdy zakładki nie mieszczą się w jednej linii. Nadmiarowe pozycje trafiają do rozwijanego menu `⋯`, które jest teleportowane do `<body>` i wyświetlane absolutnie nad układem.

## ✨ Funkcje

- ✅ Vue 3 Composition API
- ✅ Responsywne menu z automatycznym przerzucaniem zakładek do `⋯`
- ✅ `Teleport` dropdownu do `<body>` – bez rozszerzania layoutu
- ✅ Automatyczne przeliczanie pozycji dropdownu przy kliknięciu i `resize`
- ✅ Obsługa kliknięcia poza dropdownem (zamykanie)
- ✅ Wyśrodkowanie dropdownu względem przycisku `⋯`
- ✅ Estetyczne style, hover, aktywna zakładka
- 🔄 Gotowe pod rozszerzenie o animacje, `Esc`, komponenty

## 🧱 Struktura

- `menuItems`: pełna lista zakładek
- `visibleItems`: zakładki, które mieszczą się w menu
- `hiddenItems`: zakładki, które trafiają do `⋯`
- `more-button`: przycisk `⋯`, teleportowany dropdown
- `calculateVisibility()`: dynamiczne przeliczanie widocznych zakładek
- `updateDropdownPosition()`: obliczanie pozycji dropdownu względem przycisku
- `teleport`: Vue 3 `Teleport` do `<body>`

## 🚀 Jak działa

1. Przy ładowaniu i `resize` przeliczana jest dostępna szerokość menu.
2. Gdy zakładki się nie mieszczą, trafiają do `hiddenItems`.
3. Dropdown `⋯` pojawia się tylko, gdy `hiddenItems.length > 0`.
4. Dropdown teleportowany do `<body>`, wyśrodkowany względem `⋯`.
5. Po kliknięciu poza – dropdown automatycznie znika.

## 🔧 Możliwości rozbudowy

- Animacja `fade` lub `slide` dla dropdownu
- Obsługa `Esc` do zamykania
- Komponenty (`<ResponsiveMenu />`)
- Wersja mobilna z hamburgerem
- Integracja z Vue Router lub zewnętrznymi danymi

## 🖼️ Przykład

```html
<teleport to="body">
  <ul
    v-if="isMoreOpen"
    class="teleport-dropdown"
    :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }"
  >
    <li v-for="item in hiddenItems" @click="setActive(item.name)">
      {{ item.label }}
    </li>
  </ul>
</teleport>
```

## 📄 Licencja

Projekt testowo-edukacyjny – możesz używać, modyfikować i rozszerzać dowolnie.

---

## 🔗 Strona domowa

Webmaster: [asperion](http://asperion24.eu/)

📅 Data utworzenia: 2025-04-19
