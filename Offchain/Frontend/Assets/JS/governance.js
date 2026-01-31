document.addEventListener("DOMContentLoaded", () => {
  renderStats();
  renderProposals();

  const form = document.getElementById("proposalForm");
  form.addEventListener("submit", submitProposal);
});

/* =========================
   STATS
========================= */
function renderStats() {
  if (!window.GOV_DATA) return;

  document.getElementById("totalProposals").innerText =
    GOV_DATA.stats.totalProposals;

  document.getElementById("activeVotes").innerText =
    GOV_DATA.stats.activeVotes;

  document.getElementById("votingPower").innerText =
    GOV_DATA.stats.votingPower + " GRT";

  document.getElementById("walletAddress").innerText =
    GOV_DATA.wallet;
}

/* =========================
   PROPOSALS
========================= */
function renderProposals() {
  const container = document.getElementById("proposalsSection");

  if (!GOV_DATA.proposals.length) {
    container.innerHTML += "<p>No active proposals yet.</p>";
    return;
  }

  GOV_DATA.proposals.forEach(p => {
    const el = document.createElement("div");
    el.className = "proposal";

    el.innerHTML = `
      <h3>${p.title}</h3>
      <p>${p.description}</p>

      <div class="vote-bar">
        <div class="yes" style="width:${p.yes_percent}%">
          Yes ${p.yes_percent}%
        </div>
        <div class="no" style="width:${p.no_percent}%">
          No ${p.no_percent}%
        </div>
      </div>

      <div class="vote-actions">
        <button onclick="vote(${p.id}, 'yes')">Vote Yes</button>
        <button onclick="vote(${p.id}, 'no')">Vote No</button>
      </div>
    `;

    container.appendChild(el);
  });
}

/* =========================
   CREATE PROPOSAL
========================= */
async function submitProposal(e) {
  e.preventDefault();

  const title = e.target.querySelector("input").value.trim();
  const desc  = e.target.querySelector("textarea").value.trim();

  if (!title || !desc) return;

  const res = await fetch("governance.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      create_proposal: 1,
      title,
      description: desc
    })
  });

  if (res.ok) {
    location.reload();
  } else {
    alert("Failed to create proposal");
  }
}

/* =========================
   VOTE (DB ONLY)
========================= */
async function vote(proposalId, type) {
  const res = await fetch("governance.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: new URLSearchParams({
      vote: type,
      proposal_id: proposalId
    })
  });

  if (res.ok) {
    location.reload();
  } else {
    alert("Vote failed");
  }
}
