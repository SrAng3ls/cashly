# MER — Cashly

```mermaid
erDiagram
    USERS ||--o{ TRANSACTIONS : registra
    USERS ||--o{ SAVINGS_GOALS : crea
    SAVINGS_GOALS ||--o{ GOAL_CONTRIBUTIONS : recibe
    USERS ||--o{ BUDGETS : define
    BUDGETS ||--o{ BUDGET_EXPENSES : contiene
    USERS ||--o{ REMINDERS : programa
    USERS ||--o{ PASSWORD_RESETS : solicita

    USERS {
      int id PK
      varchar name
      varchar email UK
      varchar password
      enum role
      timestamp created_at
    }
    TRANSACTIONS {
      int id PK
      int user_id FK
      enum type
      decimal amount
      varchar category
      varchar payment_method
      date date
      varchar description
    }
    SAVINGS_GOALS {
      int id PK
      int user_id FK
      varchar name
      decimal target_amount
      decimal initial_amount
      date deadline
    }
    GOAL_CONTRIBUTIONS {
      int id PK
      int goal_id FK
      decimal amount
      date date
      varchar note
    }
    BUDGETS {
      int id PK
      int user_id FK
      varchar name
      varchar category
      decimal limit_amount
      enum period
      decimal initial_spent
    }
    BUDGET_EXPENSES {
      int id PK
      int budget_id FK
      int transaction_id FK
      decimal amount
      date date
      varchar description
    }
    REMINDERS {
      int id PK
      int user_id FK
      varchar title
      date event_date
      decimal amount
      enum kind
      varchar notes
    }
    PASSWORD_RESETS {
      int id PK
      int user_id FK
      char token UK
      datetime expires_at
      boolean used
    }
```

## Relaciones principales
- Un usuario puede registrar muchos movimientos.
- Un usuario puede tener muchas metas; cada meta puede tener muchos abonos.
- Un usuario puede tener muchos presupuestos; cada presupuesto puede tener muchos gastos.
- Un usuario puede crear muchos recordatorios.
- Un usuario puede solicitar varios tokens de recuperación; cada token pertenece a un usuario.
