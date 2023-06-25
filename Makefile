.PHONY: up
up: ## into container
	cd docker && docker-compose up -d

.PHONY: bash
bash: ## into container
	docker exec -ti free_php bash
