<select required name="level" id="level_<?= htmlspecialchars($skill['skill_id']) ?>">
    <?php foreach ($levels as $level): ?>
        <option value="<?= htmlspecialchars($level) ?>" <?= $level === $skill['level'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($level) ?>
        </option>
    <?php endforeach; ?>
</select>