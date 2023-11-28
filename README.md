[![](https://poggit.pmmp.io/shield.state/GiveAll)](https://poggit.pmmp.io/p/GiveAll)

[![](https://poggit.pmmp.io/shield.api/GiveAll)](https://poggit.pmmp.io/p/GiveAll)

# GiveAll
 **Give all your elements easily with a single user interface. For PocketMine-MP 5.0 servers only**

![image](https://github.com/fernanACM/GiveAll/assets/83558341/182b2538-cc7f-49a2-addd-aa520c10c112)

<a href="https://discord.gg/YyE9XFckqb"><img src="https://img.shields.io/discord/837701868649709568?label=discord&color=7289DA&logo=discord" alt="Discord" /></a>

### 📸 Images
**Form menu for one task at a time:**
<img src="https://github.com/fernanACM/GiveAll/assets/83558341/6076b0a7-869c-466f-8748-4414f3a33dba">

**Inventory menu for several tasks at the same time:**
<img src="https://github.com/fernanACM/GiveAll/assets/83558341/8b1e56de-1fe3-4406-ba88-55b180415d2f">

### 🌍 Wiki
* Check our plugin [wiki](https://github.com/fernanACM/GiveAll/wiki) for features and secrets in the...

### 💡 Implementations
* [X] Configuration
* [X] Automatic GiveAll

### 💾 Config 
```yaml
#   ____   _                     _      _   _ 
#  / ___| (_) __   __   ___     / \    | | | |
# | |  _  | | \ \ / /  / _ \   / _ \   | | | |
# | |_| | | |  \ V /  |  __/  / ___ \  | | | |
#  \____| |_|   \_/    \___| /_/   \_\ |_| |_|
#         by fernanACM
# Give items to all players on your server. Only for PocketMine-MP 5.0

# DO NOT TOUCH!
config-version: "1.0.0"
# Prefix plugin
Prefix: "&l&f[&9GiveAll&f]&8»&r "

# ==(SETTINGS)==
Settings:
  Assistant:
    # Automatic delivery assistant
    # Use true or false to activate/deactivate this option
    enabled: true
    # The time to receive the items:
    # 600 => 10 minutes
    # 300 => 5 minutes
    # More...
    delay: 600
    # Items you will receive
    items-to-receive: 3

# ==(GUI - FORM)==
Menu:
  GUI:
    Items:
      place-item: "&r&ePut the item here:"
      close-menu: "&r&l&4CLOSE MENU"
      item-send: "&r&aSend article to all"
  Form:
    content:
      - "&aAre you sure you want to give this item to everyone?"
      - "{LINE}"
      - "&l&cITEM:&r"
      - "&bName:&d {ITEM_NAME}"
      - "&bCount:&d x{ITEM_COUNT}"
    accept-button: "&l&2YES"
    deny-button: "&l&4NO"

# ==(MESSAGES)==
Messages:
  successful:
    item-received: "&aYou have received items!"
    item-sent: "&bYou have sent &ax{ITEM_COUNT}&b of&a {ITEM_NAME}&r&b to all players!"
    item-sent-auto: "&bYou have sent items to all players!"
    inventory-saved-successfully: "&aThe inventory content has been successfully saved!"
  error:
    no-item: "&cThe item is invalid!"
    no-permission: "&cYou don't have permissions for this!"
```

### 📢 Report bug
* If you find any bugs in this plugin, please let me know via: [issues](https://github.com/fernanACM/GiveAll/issues)

### 📞 Contact
| Redes | Tag | Link |
|-------|-------------|------|
| YouTube | fernanACM | [YouTube](https://www.youtube.com/channel/UC-M5iTrCItYQBg5GMuX5ySw) | 
| Discord | fernanACM#5078 | [Discord](https://discord.gg/YyE9XFckqb) |
| GitHub | fernanACM | [GitHub](https://github.com/fernanACM)
| Poggit | fernanACM | [Poggit](https://poggit.pmmp.io/ci/fernanACM)
****

### ✔ Credits
| Authors | Github | Lib |
|---------|--------|-----|
| Vecnavium | [Vecnavium](https://github.com/Vecnavium) | [FormsUI](https://github.com/Vecnavium/FormsUI/tree/master/) |
| CortexPE | [CortexPE](https://github.com/CortexPE) | [Commando](https://github.com/CortexPE/Commando/tree/master/) |
| Muqsit | [Muqsit](https://github.com/Muqsit) | [SimplePacketHandler](https://github.com/Muqsit/SimplePacketHandler) |
| Muqsit | [Muqsit](https://github.com/Muqsit) | [InvMenu](https://github.com/Muqsit/InvMenu) |
| DaPigGuy | [DaPigGuy](https://github.com/DaPigGuy) | [libPiggyUpdateChecker](https://github.com/DaPigGuy/libPiggyUpdateChecker) |
****
