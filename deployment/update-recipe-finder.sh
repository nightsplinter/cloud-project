#!/bin/bash
# This script is used to update the recipe-finder application on the linux vm

# Check with a dry-run if the playbook is correct
# ansible-playbook -i inventory site.yml --vault-password-file ~/.vault_pass --check

# Run the playbook
ansible-playbook -i inventory.yml site.yml  --vault-password-file ./.vault_pass.txt

# Edit the vault password file
# cd group_vars/recip-finder
# ansible-vault edit vault.yml --vault-password-file ~/.vault_pass
