<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <title>Vue 3 Teleport Dropdown</title>
  <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
    }

    .menu-container {
      background-color: #333;
      overflow: hidden;
    }

    .menu {
      display: flex;
      list-style: none;
      margin: 0;
      padding: 0;
      white-space: nowrap;
    }

    .menu-item {
      padding: 14px 20px;
      color: white;
      cursor: pointer;
    }

    .menu-item:hover {
      background-color: #444;
    }

    .menu-item.active {
      background-color: #007bff;
    }

    .more-button {
      padding: 14px 20px;
      color: white;
      cursor: pointer;
    }

   .teleport-dropdown {
  position: absolute;
  background-color: #333;
  border: 1px solid #444;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
  list-style: none;
  padding: 0;
  margin: 0;
  z-index: 9999;

  max-width: 300px;        /* ⬅️ max szerokość */
  overflow-x: auto;        /* ⬅️ tylko jeśli chcesz przewijanie */
  white-space: normal;     /* ⬅️ pozwala zawijać tekst */
  box-sizing: border-box;
}


    .teleport-dropdown li {
      padding: 12px 20px;
      color: white;
      cursor: pointer;
    }

    .teleport-dropdown li:hover {
      background-color: #555;
    }

    .content {
      padding: 20px;
    }
  </style>
</head>
<body>
  <div id="app" style="margin-top:5em;">
    <div class="menu-container" ref="menuContainer">
      <ul class="menu" ref="menuList">
        <li
          v-for="item in visibleItems"
          :key="item.name"
          class="menu-item"
          :class="{ active: activeItem === item.name }"
          @click="setActive(item.name)"
        >
          {{ item.label }}
        </li>

        <li>
<li v-if="hiddenItems.length > 0">
  <div class="more-button" ref="moreButton" @click="toggleMore">⋯</div>
</li>
        </li>
      </ul>
    </div>

    <!-- Teleport destination -->
    <teleport to="body">
      <ul
        v-if="isMoreOpen"
        class="teleport-dropdown"
        :style="{ top: dropdownPosition.top + 'px', left: dropdownPosition.left + 'px' }"
      >
        <li
          v-for="item in hiddenItems"
          :key="item.name"
          @click="setActive(item.name); isMoreOpen = false"
        >
          {{ item.label }}
        </li>
      </ul>
    </teleport>

    <div class="content">
      <h2>Aktualna sekcja: {{ activeItem }}</h2>
      <p>{{ getContentFor(activeItem) }}</p>
    </div>
  </div>

  <script>
    const { createApp, ref, onMounted, nextTick, onBeforeUnmount } = Vue;

    createApp({
      setup() {
        const menuItems = ref([
          { name: "home", label: "Strona główna" },
          { name: "about", label: "O nas" },
          { name: "services", label: "Usługi" },
          { name: "products", label: "Produkty" },
          { name: "contact", label: "Kontakt" },
          { name: "faq", label: "FAQ" },
          { name: "blog", label: "Blog" },
          { name: "support", label: "Wsparcie" },
          { name: "terms", label: "Regulamin" },
        ]);

        const activeItem = ref("home");
        const visibleItems = ref([]);
        const hiddenItems = ref([]);
        const isMoreOpen = ref(false);
        const dropdownPosition = ref({ top: 0, left: 0 });

        const menuContainer = ref(null);
        const menuList = ref(null);
        const moreButton = ref(null);

        const setActive = (name) => {
          activeItem.value = name;
        };

        const getContentFor = (name) => {
          const content = {
            home: "Witamy na naszej stronie!",
            about: "O nas...",
            services: "Usługi",
            products: "Produkty",
            contact: "Kontakt",
            faq: "FAQ",
            blog: "Blog",
            support: "Wsparcie",
            terms: "Regulamin",
          };
          return content[name] || "";
        };

        const calculateVisibility = () => {
          visibleItems.value = [];
          hiddenItems.value = [];

          const containerWidth = menuContainer.value.offsetWidth;
          const buffer = 60;
          let totalWidth = 0;

          for (let i = 0; i < menuItems.value.length; i++) {
            const temp = document.createElement("li");
            temp.className = "menu-item";
            temp.style.visibility = "hidden";
            temp.style.position = "absolute";
            temp.style.whiteSpace = "nowrap";
            temp.innerText = menuItems.value[i].label;

            menuList.value.appendChild(temp);
            const width = temp.offsetWidth;
            menuList.value.removeChild(temp);

            if (totalWidth + width + buffer <= containerWidth) {
              visibleItems.value.push(menuItems.value[i]);
              totalWidth += width;
            } else {
              hiddenItems.value = menuItems.value.slice(i);
              break;
            }
          }
        };

const updateDropdownPosition = () => {
  if (isMoreOpen.value && moreButton.value) {
    const rect = moreButton.value.getBoundingClientRect();
    const dropdownWidth = 300; // jeśli potrzebujesz do sprawdzenia overflow
let left = rect.left + window.scrollX + rect.width / 2 - dropdownWidth / 2;

  
    if (left < 0) left = 0; // nie wyjść poza ekran

    dropdownPosition.value = {
      top: rect.bottom + window.scrollY,
      left: left,
    };
  }
};

  const toggleMore = () => {
  isMoreOpen.value = !isMoreOpen.value;

  if (isMoreOpen.value) {
    nextTick(() => {
      updateDropdownPosition();
    });
  }
};


        const handleClickOutside = (event) => {
          if (
            isMoreOpen.value &&
            !menuList.value.contains(event.target) &&
            !document.querySelector(".teleport-dropdown")?.contains(event.target)
          ) {
            isMoreOpen.value = false;
          }
        };

        onMounted(() => {
          visibleItems.value = menuItems.value.slice();
          nextTick(() => {
            calculateVisibility();
window.addEventListener("resize", () => {
  nextTick(() => {
    calculateVisibility();
    updateDropdownPosition(); // 👈 dodaj to!
  });
});
            document.addEventListener("click", handleClickOutside);
          });
        });

        onBeforeUnmount(() => {
          window.removeEventListener("resize", calculateVisibility);
          document.removeEventListener("click", handleClickOutside);
        });

        return {
          menuItems,
          visibleItems,
          hiddenItems,
          activeItem,
          isMoreOpen,
          dropdownPosition,
          toggleMore,
          setActive,
          getContentFor,
          menuContainer,
          menuList,
          moreButton,
        };
      },
    }).mount("#app");
  </script>
</body>
</html>
