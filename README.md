# Food Order

# Locking

```flowchart

                ┌───────────────────────────────────────────────┐
                │          User Requests to Buy Product         │
                └───────────────────────────────────────────────┘
                                        │
                                        ▼
                ┌───────────────────────────────────────────────┐
                │      Attempt to Acquire Lock in Redis         │
                └───────────────────────────────────────────────┘
                                        │
                    ┌───────────────────┴──────────────────────────┐
            Lock Acquired                                Lock Exists (Retry)
                    │                                              │
                    ▼                                              ▼
    ┌──────────────────────────────┐               ┌───────────────────────────────────┐
    │  Update Inventory in MySQL   │               │ Retry up to 10 times (50ms delay) │
    └──────────────────────────────┘               └───────────────────────────────────┘
                    │                                              │
        ┌───────────┴─────────────┐                        ┌───────┴───────┐
    Success                     Failed                 Failed After Max Retries
        │                          │                               │
        ▼                          ▼                               ▼
    ┌─────────────────┐    ┌────────────────┐         ┌───────────────────────────┐
    │ Set Lock Expiry │    │ Return Error   │         │ Notify User: Out of Stock │
    └─────────────────┘    └────────────────┘         └───────────────────────────┘
            │
            ▼
    ┌─────────────────────────────┐
    │ Proceed to Checkout/Payment │
    └─────────────────────────────┘
            │
            ▼
    ┌───────────────────────────┐
    │      Free Redis Lock      │
    └───────────────────────────┘

```

# Comments

-   Formula delete comment

```
  width = right - left + 1
  width ==> 8 - 3 + 1 = 6 (border)
```

```flowchart
Comment 1
│
├── comment 1.1
│   ├── comment 1.1.1
│       ├── comment 1.1.1.1
│       └── comment 1.1.1.2
│
└── comment 1.2
    ├── comment 1.2.1
    │   ├── comment 1.2.1.1
    │   └── comment 1.2.1.2
    ├── comment 1.2.2
    └── comment 1.2.3
```
