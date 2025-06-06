    <style>
        .productos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            padding: 1rem;
        }

        .producto-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .producto-imagen {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.05);
        }

        .producto-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .producto-card h3 {
            color: var(--text-color);
            font-size: 1.2rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .producto-card p {
            color: var(--text-secondary);
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .btn-comprar {
            background: var(--primary-color);
            color: white;
            padding: 0.7rem 1.2rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: auto;
            font-size: 0.9rem;
        }

        .empty-message {
            text-align: center;
            color: var(--text-secondary);
            padding: 2rem;
            grid-column: 1 / -1;
        }
    </style> 