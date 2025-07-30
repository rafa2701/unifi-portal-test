<template>
  <div class="controllers-view">
    <div class="controllers-content">
      <div class="action-cards">
        <a href="post-new.php?post_type=unifi-controller" class="card-link">
          <BaseCard hover class="action-card">
            <template #icon>
              <Plus class="card-icon" />
            </template>
            <h3>Add Controller</h3>
            <p>Configure a new UniFi network controller</p>
          </BaseCard>
        </a>

        <a href="edit.php?post_type=unifi-controller" class="card-link">
          <BaseCard hover class="action-card">
            <template #icon>
              <List class="card-icon" />
            </template>
            <h3>View Controllers</h3>
            <p>Manage existing network controllers</p>
          </BaseCard>
        </a>
      </div>
    </div>
  </div>
</template>

<script>
import BaseCard from "@admin/components/base/BaseCard.vue";
import { Plus, List } from "lucide-vue-next";

export default {
  name: "ControllersView",
  components: {
    BaseCard,
    Plus,
    List,
  },
};
</script>

<style lang="scss">
.controllers-view {
  padding: map.get($spacing, "xl") 0;

  .controllers-content {
    max-width: 800px;
    margin: 0 auto;
  }

  .card-link {
    text-decoration: none;
  }

  .action-cards {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: map.get($spacing, "xl");

    .action-card {
      text-align: center;
      padding: map.get($spacing, "2xl");
      transition: transform 0.2s ease;

      &:hover {
        transform: translateY(-4px);
      }

      .card-icon {
        width: 2.5rem;
        height: 2.5rem;
        color: map.get($colors, "blue", 500);
        margin-bottom: map.get($spacing, "lg");
      }

      h3 {
        font-size: map.get($font-size, "xl");
        color: map.get($colors, "slate", 900);
        margin-bottom: map.get($spacing, "sm");
      }

      p {
        color: map.get($colors, "slate", 600);
        font-size: map.get($font-size, "base");
        line-height: 1.5;
      }
    }
  }
}
</style>
