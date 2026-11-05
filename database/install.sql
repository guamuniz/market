CREATE TABLE IF NOT EXISTS admins (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 last_login DATETIME NULL,
 created_at DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS analytics_events (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 session_key VARCHAR(128) NOT NULL,
 visitor_hash CHAR(64) NOT NULL,
 event_name VARCHAR(40) NOT NULL,
 target VARCHAR(190) NULL,
 page_path VARCHAR(500) NOT NULL,
 user_agent VARCHAR(255) NULL,
 created_at DATETIME NOT NULL,
 INDEX idx_event_created (event_name, created_at),
 INDEX idx_visitor_created (visitor_hash, created_at),
 INDEX idx_page_created (page_path(190), created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS market_cache (
 cache_key VARCHAR(80) PRIMARY KEY,
 payload MEDIUMTEXT NOT NULL,
 fetched_at DATETIME NOT NULL,
 INDEX idx_fetched_at (fetched_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
