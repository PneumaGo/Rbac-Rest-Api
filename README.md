# RBAC REST API (Laravel 12)

Цей проєкт реалізує систему управління користувачами з доступом на основі ролей (**RBAC**). Система розділяє права між трьома типами користувачів: **Root**, **Admin** та **Operator**.

## 🚀 Технологічний стек
- **Framework:** Laravel 12
- **Auth:** Laravel Sanctum
- **Database:** MySQL
- **Testing:** PHPUnit (Feature Tests)

---

## 🔐 Рольова модель

| Функціонал | Root | Admin | Operator |
| :--- | :---: | :---: | :---: |
| Перегляд списку всіх користувачів | ✅ | ✅ | ❌ |
| Створення нових Admin/Operator | ✅ | ✅ (тільки Operator) | ❌ |
| Редагування свого профілю | ✅ | ✅ | ✅ |
| Зміна ролей користувачів | ✅ | ❌ | ❌ |
| Видалення користувачів | ✅ | ❌ | ❌ |

---

## 🛠 Встановлення та запуск

### 1. Клонування проєкту
```bash
git clone [https://github.com/PneumaGo/Rbac-Rest-Api.git](https://github.com/PneumaGo/Rbac-Rest-Api.git)
cd Rbac-Rest-Api
