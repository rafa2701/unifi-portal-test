<template>
  <div class="docs-view">
    <div class="docs-content">
      <BaseCard class="doc-section">
        <h3>Installation</h3>
        <div class="doc-text">
          <p>
            1. Upload the plugin files to
            <code>/wp-content/plugins/unifi-portal</code>
          </p>
          <p>2. Activate the plugin through the 'Plugins' menu in WordPress</p>
          <p>3. Configure your first controller in the UniFi Portal settings</p>
        </div>
      </BaseCard>

      <BaseCard class="doc-section">
        <h3>Template</h3>
        <div class="shortcode-list">
          <div class="shortcode-item">
            <code>Unifi Portal Template</code>
            <p>Displays the full UniFi management portal</p>
          </div>
        </div>
      </BaseCard>

      <BaseCard class="doc-section">
        <h3>Support</h3>
        <div class="support-links">
          <a href="#" class="support-link">
            <FileText class="link-icon" />
            <span>Documentation</span>
          </a>
          <a href="#" class="support-link">
            <MessageCircle class="link-icon" />
            <span>Support Forum</span>
          </a>
          <a href="#" class="support-link">
            <Mail class="link-icon" />
            <span>Contact Support</span>
          </a>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script>
import BaseCard from "@admin/components/base/BaseCard.vue";
import { FileText, MessageCircle, Mail } from "lucide-vue-next";

export default {
  name: "DocsView",
  components: {
    BaseCard,
    FileText,
    MessageCircle,
    Mail,
  },
};
</script>

<style lang="scss">
.docs-view {
  padding: map.get($spacing, "xl") 0;

  .docs-content {
    max-width: 800px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "xl");
  }

  .doc-section {
    h3 {
      font-size: map.get($font-size, "xl");
      color: map.get($colors, "slate", 900);
      margin-bottom: map.get($spacing, "lg");
    }
  }

  .doc-text {
    p {
      margin-bottom: map.get($spacing, "md");
      color: map.get($colors, "slate", 600);
      line-height: 1.6;

      code {
        font-family: map.get($font-family, "mono");
        background: map.get($colors, "slate", 100);
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
      }
    }
  }

  .shortcode-list {
    display: flex;
    flex-direction: column;
    gap: map.get($spacing, "lg");

    .shortcode-item {
      code {
        display: inline-block;
        font-family: map.get($font-family, "mono");
        background: map.get($colors, "slate", 100);
        padding: map.get($spacing, "sm") map.get($spacing, "md");
        border-radius: 0.25rem;
        margin-bottom: map.get($spacing, "xs");
      }

      p {
        color: map.get($colors, "slate", 600);
        font-size: map.get($font-size, "sm");
      }
    }
  }

  .support-links {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: map.get($spacing, "md");

    .support-link {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: map.get($spacing, "lg");
      text-decoration: none;
      color: map.get($colors, "slate", 700);
      background: map.get($colors, "slate", 50);
      border-radius: 0.5rem;
      transition: all 0.2s ease;

      &:hover {
        background: map.get($colors, "slate", 100);
        transform: translateY(-2px);
      }

      .link-icon {
        width: 1.5rem;
        height: 1.5rem;
        margin-bottom: map.get($spacing, "sm");
        color: map.get($colors, "blue", 500);
      }

      span {
        font-size: map.get($font-size, "sm");
        font-weight: 500;
      }
    }
  }
}
</style>
